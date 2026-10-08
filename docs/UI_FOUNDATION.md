# MURIALO - UI Foundation & Design System Specification

## 1. Visi & Filosofi Desain

MURIALO adalah platform SaaS rekrutmen cerdas enterprise untuk perusahaan. Filosofi visual yang diusung adalah:
- **Profesional & Tenang**: Menghadirkan antarmuka B2B modern dengan palet warna biru korporat yang menenangkan dan membangun kredibilitas.
- **Kejelasan Data (Data Clarity)**: Prioritas pada kemudahan membaca tabel, metrik KPI, status lamaran, dan hasil analisis AI tanpa visual clutter.
- **Transparansi Beretika**: Mengedepankan prinsip *Human-in-the-loop*. Hasil AI (skill match, scoring, deteksi anomali) disajikan sebagai rekomendasi dan sinyal panduan, bukan keputusan mutlak.
- **Fokus Ujian**: Area pengerjaan tes esai kandidat dirancang bebas distraksi (*zen-mode*) untuk kenyamanan kognitif pelamar.

---

## 2. Design Tokens Terpusat

### A. Palet Warna (Color System)
Semua warna didefinisikan secara baku pada token Tailwind CSS v4 di `resources/css/app.css`:

| Token | Nilai Hex | Penggunaan Utama |
|---|---|---|
| **Primary** | `#2563EB` (Blue 600) | Tombol aksi utama, tab aktif, link, aksen brand |
| **Primary Hover** | `#1D4ED8` (Blue 700) | State hover tombol primary |
| **Primary Soft** | `#EFF6FF` (Blue 50) | Background badge, highlight baris, kartu aktif |
| **Background** | `#F8FAFC` (Slate 50) | Warna latar belakang seluruh halaman dashboard |
| **Surface** | `#FFFFFF` (White) | Kartu, modal, tabel, topbar, sidebar |
| **Text Primary** | `#0F172A` (Slate 900) | Judul, angka metrik, label utama |
| **Text Secondary** | `#64748B` (Slate 500) | Deskripsi, subtitle, placeholder, meta teks |
| **Border** | `#E2E8F0` (Slate 200) | Garis batas kartu, pemisah tabel, border input |
| **Success** | `#10B981` (Emerald 500) | Status Lolos, Diterima, Skor tinggi (>=85%) |
| **Warning** | `#F59E0B` (Amber 500) | Status Menunggu Review, Skor moderat (70-84%) |
| **Danger / Risk** | `#EF4444` (Rose 500) | Status Ditolak, Anomali Tinggi, Peringatan |

### B. Tipografi
- **Font Utama**: `Plus Jakarta Sans`, dengan fallback `Inter`, `system-ui`, `-apple-system`, `sans-serif`.
- **Hierarki Skala Tipografi**:
  - `Display / Hero Title`: 36px – 48px (`text-4xl` s/d `text-5xl`), font-bold (Landing Page)
  - `Page Heading (H1)`: 20px – 24px (`text-xl` s/d `text-2xl`), font-bold (Dashboard Topbar)
  - `Section Heading (H2/H3)`: 16px – 18px (`text-base` s/d `text-lg`), font-semibold
  - `Body Text`: 14px (`text-sm`), font-normal / font-medium
  - `Caption / Meta / Badge`: 11px – 12px (`text-[11px]` s/d `text-xs`)
  - `Data Angka / Skor`: Monospace font `font-mono` untuk angka probabilitas, skor, dan timestamp.

### C. Spacing & Radius
- **Card Radius**: `rounded-xl` (12px) hingga `rounded-2xl` (16px).
- **Button & Input Radius**: `rounded-lg` (8px) hingga `rounded-xl` (10px).
- **Badge Radius**: `rounded-full` atau `rounded-md` (4px).
- **Grid Layout Spacing**: Konsisten menggunakan gap 16px (`gap-4`) hingga 24px (`gap-6`).

### D. Elevasi & Bayangan (Shadows)
- Default Card: `border border-slate-200 shadow-sm`
- Dropdown & Popover: `shadow-md`
- Modal & Sticky Bar: `shadow-xl border border-slate-200`

---

## 3. Sistem Layout

### A. Layout Publik (`layouts/public.blade.php`)
- Header navigasi fixed dengan backdrop blur (`bg-white/90 backdrop-blur-md`).
- Container terpusat maksimal `max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`.
- Footer lengkap dengan tautan karir, legalitas, dan kontak resmi.

### B. Layout Autentikasi (`layouts/auth.blade.php`)
- Split-screen visual: Sisi kiri menampilkan benefit & reputasi platform, sisi kanan form autentikasi terfokus.
- Tombol demo 1-click role switcher mempermudah pengujian tim.

### C. Layout HR & Admin (`layouts/hr.blade.php`, `layouts/admin.blade.php`)
- **Desktop Sidebar**: Lebar tetap 256px (`w-64`), berisi navigasi bertingkat yang dikelompokkan ke:
  1. *Ringkasan*: Dashboard utama.
  2. *Rekrutmen*: Lowongan, Pelamar, Pipeline Kanban.
  3. *Evaluasi*: CV Masuk, Automated Skill Matching, Smart Grading Test.
  4. *Insight*: Rekomendasi Talenta, Deteksi Anomali, Analitik Rekrutmen.
  5. *Administrasi*: Pengguna, Perizinan, Pengaturan, Audit Log.
- **Desktop Topbar**: Tinggi 64px (`h-16`), sticky top, memuat judul halaman, status badge demo, quick role switcher, notifikasi, dan profil user.
- **Mobile Responsive**: Navigation drawer off-canvas yang dapat dibuka-tutup dengan smooth transition.

### D. Layout Ujian Fokus (`layouts/focus.blade.php`)
- Minimalis tanpa sidebar dan distraksi navigasi keluar.
- Timer hitung mundur terintegrasi dengan indikator status koneksi/autosave lokal.

---

## 4. Reusable Blade Components

Komponen terletak di `resources/views/components/`:
1. `<x-button>`: Varian primary, secondary, danger, ghost, dan link dengan state loading.
2. `<x-input>`: Input teks/email/password dengan icon slot, label, dan error feedback.
3. `<x-select>`: Dropdown select terstandarisasi.
4. `<x-textarea>`: Textarea untuk deskripsi atau catatan reviewer.
5. `<x-checkbox>`: Checkbox interaktif.
6. `<x-card>`: Kartu kontainer seragam dengan header, body, dan footer terpisah.
7. `<x-badge>`: Label status berwarna semantik (success, warning, danger, info, neutral).
8. `<x-alert>`: Banner edukasi dan notifikasi kontekstual.
9. `<x-modal>`: Dialog modal Alpine.js dengan dukungan tombol `Escape` dan click outside.
10. `<x-tabs>`: Tab navigasi switching untuk detail pelamar dan lowongan.
11. `<x-empty-state>`: Tampilan state kosong jika belum ada data.
12. `<x-skeleton>`: Placeholder loading animasi shimming.
13. `<x-toast>`: Notifikasi mengambang dengan auto-dismiss.
14. `<x-pagination>`: Navigasi halaman data tabel.
