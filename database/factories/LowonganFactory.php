<?php

namespace Database\Factories;

use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lowongan>
 */
class LowonganFactory extends Factory
{
    protected $model = Lowongan::class;

    public function definition(): array
    {
        $judulPosisi = [
            'Laravel Developer', 'Backend Engineer', 'Frontend Developer',
            'Full Stack Developer', 'Data Analyst', 'UI/UX Designer',
            'Project Manager', 'DevOps Engineer', 'Mobile Developer',
            'QA Engineer', 'System Administrator', 'Database Administrator',
            'Machine Learning Engineer', 'Cyber Security Analyst',
            'Technical Writer', 'Scrum Master', 'Business Analyst',
            'Software Architect', 'Cloud Engineer', 'Product Manager',
        ];

        $perusahaan = [
            'PT Murialo Teknologi', 'PT Nusantara Digital', 'PT Karya Solusi Indonesia',
            'CV Inovasi Kreatif', 'PT Bintang Cemerlang', 'PT Maju Bersama',
            'PT Solusi Data Pintar', 'PT Teknologi Masa Depan', 'PT Kreatif Nusantara',
            'PT Global Inovasi', 'PT Sinergi Teknologi', 'PT Digital Andalan',
        ];

        $lokasi = [
            'Jakarta', 'Surabaya', 'Bandung', 'Semarang', 'Yogyakarta',
            'Malang', 'Kudus', 'Solo', 'Denpasar', 'Medan',
            'Makassar', 'Remote', 'Hybrid - Jakarta', 'Hybrid - Surabaya',
        ];

        $skillSets = [
            'PHP, Laravel, MySQL, REST API, Git',
            'JavaScript, React, TypeScript, Tailwind CSS',
            'Python, FastAPI, PostgreSQL, Docker',
            'Java, Spring Boot, Microservices, Kafka',
            'Flutter, Dart, Firebase, REST API',
            'Go, gRPC, Redis, Kubernetes',
            'Vue.js, Nuxt.js, CSS, HTML, JavaScript',
            'Node.js, Express, MongoDB, GraphQL',
            'AWS, Docker, Terraform, CI/CD, Linux',
            'Figma, Adobe XD, CSS, HTML, Prototyping',
            'SQL, Power BI, Python, Data Visualization',
            'Kotlin, Android, Jetpack Compose, MVVM',
        ];

        $gajiMin = fake()->optional(0.7)->randomElement([
            3000000, 4000000, 5000000, 6000000, 7000000,
            8000000, 10000000, 12000000, 15000000, 20000000,
        ]);

        $gajiMax = $gajiMin !== null
            ? $gajiMin + fake()->randomElement([1000000, 2000000, 3000000, 5000000, 8000000])
            : fake()->optional(0.3)->randomElement([8000000, 10000000, 15000000, 20000000]);

        return [
            'user_id' => User::factory(),
            'judul' => fake()->randomElement($judulPosisi),
            'perusahaan' => fake()->randomElement($perusahaan),
            'lokasi' => fake()->randomElement($lokasi),
            'tipe_pekerjaan' => fake()->randomElement(array_keys(Lowongan::TIPE)),
            'deskripsi' => implode("\n\n", [
                'Kami sedang mencari kandidat yang bersemangat untuk bergabung dengan tim kami.',
                'Tanggung jawab utama meliputi pengembangan dan pemeliharaan aplikasi, kolaborasi dengan tim lintas fungsi, serta berkontribusi pada peningkatan kualitas produk.',
                'Kami menawarkan lingkungan kerja yang dinamis, peluang pengembangan karir, serta kompensasi yang kompetitif.',
            ]),
            'persyaratan' => implode("\n", [
                '- Minimal pengalaman '.fake()->randomElement(['1', '2', '3', '5']).' tahun di bidang terkait',
                '- Pendidikan minimal '.fake()->randomElement(['D3', 'S1', 'S1/S2']).' Teknik Informatika atau sejenis',
                '- Mampu bekerja secara mandiri maupun dalam tim',
                '- Memiliki kemampuan komunikasi yang baik',
                '- Bersedia bekerja di '.fake()->randomElement(['kantor', 'remote', 'hybrid']),
            ]),
            'skills' => fake()->optional(0.85)->randomElement($skillSets),
            'gaji_min' => $gajiMin,
            'gaji_max' => $gajiMax,
            'batas_lamaran' => fake()->optional(0.8)->dateTimeBetween('+1 week', '+3 months')?->format('Y-m-d'),
            'status' => fake()->randomElement(array_keys(Lowongan::STATUS)),
        ];
    }

    public function aktif(): static
    {
        return $this->state(fn () => ['status' => 'aktif']);
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }

    public function ditutup(): static
    {
        return $this->state(fn () => ['status' => 'ditutup']);
    }
}
