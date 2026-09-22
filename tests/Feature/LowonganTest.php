<?php

namespace Tests\Feature;

use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LowonganTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'judul' => 'Laravel Developer', 'perusahaan' => 'Murialo', 'lokasi' => 'Kudus',
            'tipe_pekerjaan' => 'full_time', 'deskripsi' => 'Mengembangkan aplikasi web.',
            'persyaratan' => 'Menguasai PHP.', 'skills' => 'PHP, Laravel, MySQL',
            'gaji_min' => 5000000, 'gaji_max' => 8000000, 'batas_lamaran' => '2026-12-31', 'status' => 'draft',
        ], $overrides);
    }

    private function vacancy(User $owner, array $overrides = []): Lowongan
    {
        $lowongan = new Lowongan($this->payload($overrides));
        $lowongan->user()->associate($owner);
        $lowongan->save();

        return $lowongan;
    }

    public function test_guests_cannot_access_any_crud_action(): void
    {
        $lowongan = $this->vacancy(User::factory()->create());
        foreach (['/lowongan', '/lowongan/create', '/lowongan/'.$lowongan->id, '/lowongan/'.$lowongan->id.'/edit'] as $url) {
            $this->get($url)->assertUnauthorized();
        }
        $this->post('/lowongan', $this->payload())->assertUnauthorized();
        $this->put('/lowongan/'.$lowongan->id, $this->payload())->assertUnauthorized();
        $this->delete('/lowongan/'.$lowongan->id)->assertUnauthorized();
    }

    public function test_basic_auth_accepts_valid_credentials_and_rejects_invalid_credentials(): void
    {
        $user = User::factory()->create(['password' => 'secret-password']);
        $this->withServerVariables(['PHP_AUTH_USER' => $user->email, 'PHP_AUTH_PW' => 'wrong'])->get('/lowongan')->assertUnauthorized();
        $this->withServerVariables(['PHP_AUTH_USER' => $user->email, 'PHP_AUTH_PW' => 'secret-password'])->get('/lowongan')->assertOk();
    }

    public function test_owner_can_create_read_update_and_delete_lowongan(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/lowongan/create')->assertOk();
        $this->post('/lowongan', $this->payload(['user_id' => 999]))->assertRedirect();
        $lowongan = Lowongan::sole();
        $this->assertSame($user->id, $lowongan->user_id);
        $this->get('/lowongan')->assertOk()->assertSee('Laravel Developer');
        $this->get('/lowongan/'.$lowongan->id)->assertOk()->assertSee('PHP')->assertSee('Laravel')->assertSee('MySQL');
        $this->get('/lowongan/'.$lowongan->id.'/edit')->assertOk();
        $this->put('/lowongan/'.$lowongan->id, $this->payload(['judul' => 'Backend Engineer', 'status' => 'aktif']))->assertRedirect('/lowongan/'.$lowongan->id);
        $this->assertDatabaseHas('lowongan', ['id' => $lowongan->id, 'judul' => 'Backend Engineer', 'status' => 'aktif']);
        $this->delete('/lowongan/'.$lowongan->id)->assertRedirect('/lowongan');
        $this->assertDatabaseMissing('lowongan', ['id' => $lowongan->id]);
    }

    public function test_other_users_cannot_read_edit_update_or_delete_lowongan(): void
    {
        $lowongan = $this->vacancy(User::factory()->create());
        $this->actingAs(User::factory()->create());
        $this->get('/lowongan')->assertOk()->assertDontSee('Laravel Developer');
        $this->get('/lowongan/'.$lowongan->id)->assertForbidden();
        $this->get('/lowongan/'.$lowongan->id.'/edit')->assertForbidden();
        $this->put('/lowongan/'.$lowongan->id, $this->payload())->assertForbidden();
        $this->delete('/lowongan/'.$lowongan->id)->assertForbidden();
        $this->assertDatabaseCount('lowongan', 1);
    }

    public function test_invalid_fields_and_salary_range_are_rejected_without_writing_data(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post('/lowongan', $this->payload(['judul' => '', 'gaji_min' => -1, 'status' => 'invalid', 'tipe_pekerjaan' => 'invalid', 'batas_lamaran' => '2026-02-30']))
            ->assertSessionHasErrors(['judul', 'gaji_min', 'status', 'tipe_pekerjaan', 'batas_lamaran']);
        $this->post('/lowongan', $this->payload(['gaji_max' => 100]))->assertSessionHasErrors('gaji_max');
        $this->assertDatabaseCount('lowongan', 0);
    }

    public function test_optional_fields_can_be_cleared_and_expired_vacancies_can_be_edited(): void
    {
        $user = User::factory()->create();
        $lowongan = $this->vacancy($user);
        $this->actingAs($user)->put('/lowongan/'.$lowongan->id, $this->payload(['gaji_min' => '', 'gaji_max' => '', 'skills' => '', 'batas_lamaran' => '2020-01-01', 'status' => 'ditutup']))->assertSessionHasNoErrors();
        $this->assertNull($lowongan->fresh()->gaji_min);
        $this->assertNull($lowongan->fresh()->skills);
    }

    public function test_search_and_status_filters_stay_scoped_to_owner(): void
    {
        $owner = User::factory()->create();
        $this->vacancy($owner, ['judul' => 'Cocok', 'status' => 'aktif']);
        $this->vacancy($owner, ['judul' => 'Draft rahasia', 'status' => 'draft']);
        $this->vacancy(User::factory()->create(), ['judul' => 'Milik orang lain', 'status' => 'aktif']);
        $this->actingAs($owner)->get('/lowongan?q=Kudus&status=aktif')->assertOk()->assertSee('Cocok')->assertDontSee('Draft rahasia')->assertDontSee('Milik orang lain');
        $this->get('/lowongan?q=tidak-ada')->assertSee('Belum ada lowongan yang sesuai');
    }

    public function test_pagination_preserves_filters_and_content_is_escaped(): void
    {
        $owner = User::factory()->create();
        for ($i = 0; $i < 11; $i++) {
            $this->vacancy($owner, ['judul' => 'Posisi '.$i]);
        }
        $this->actingAs($owner)->get('/lowongan?q=Posisi&status=draft')->assertOk()->assertSee('Halaman 1 dari 2')->assertSee('page=2');
        $lowongan = $this->vacancy($owner, ['deskripsi' => '<script>alert(1)</script>']);
        $this->get('/lowongan/'.$lowongan->id)->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
        $this->get('/lowongan/99999')->assertNotFound();
    }
}
