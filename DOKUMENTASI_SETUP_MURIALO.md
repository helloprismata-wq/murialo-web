# 📘 Dokumentasi Lengkap Setup Project Murialo

**Owner/Pemilik Repo**: `helloprismata-wq`
**Repository**: `murialo-web` (Laravel 12) & `murialo-ai-engine` (Python FastAPI)
**Tim**: Khamdanul Jiyad, Muhammad Adi Pratama, Satyatma Raka Wiratama, Ahmad Sulthon Fajar Nailul Muna, Habib Hadi Widjanarko

Dokumen ini mencakup **seluruh proses setup dari nol** — dari bikin repo GitHub sampai kedua project siap dipakai coding bareng tim. Simpan dokumen ini sebagai referensi bersama.

---

## 📋 Daftar Isi

1. [Konsep Dasar Branch](#1-konsep-dasar-branch)
2. [Persiapan Awal](#2-persiapan-awal)
3. [Personal Access Token](#3-personal-access-token)
4. [Membuat Repository](#4-membuat-repository)
5. [Invite Anggota Tim (Collaborator)](#5-invite-anggota-tim-collaborator)
6. [Clone Repository](#6-clone-repository)
7. [Membuat Branch develop dan Branch Fitur](#7-membuat-branch-develop-dan-branch-fitur)
8. [Branch Protection Ruleset](#8-branch-protection-ruleset)
9. [Mengubah Default Branch](#9-mengubah-default-branch)
10. [Setup Project murialo-web (Laravel 12)](#10-setup-project-murialo-web-laravel-12)
11. [Setup Project murialo-ai-engine (FastAPI)](#11-setup-project-murialo-ai-engine-fastapi)
12. [Tabel Pembagian Branch per Orang](#12-tabel-pembagian-branch-per-orang)
13. [Workflow Kerja Harian](#13-workflow-kerja-harian)
14. [Tracking Tugas dengan GitHub Projects](#14-tracking-tugas-dengan-github-projects)
15. [Troubleshooting](#15-troubleshooting)
16. [Status Akhir & Checklist](#16-status-akhir--checklist)
17. [Langkah Selanjutnya](#17-langkah-selanjutnya)

---

## 1. Konsep Dasar Branch

Sebelum mulai, pahami dulu fungsi tiap jenis branch:

| Branch | Fungsi |
|---|---|
| `main` | Versi final/stabil, siap rilis ke production. **Jangan disentuh langsung** selama masih tahap development |
| `develop` | "Markas" tempat semua fitur digabung dan diuji bareng sebelum layak naik ke `main` |
| `fitur/...` | Tempat tiap anggota tim ngoding fitur masing-masing |

**Alur kerja:**
```
fitur/xxx  →  (Pull Request + review)  →  develop  →  (kalau sudah matang)  →  main
```

Default branch kedua repo diarahkan ke `develop` (bukan `main`), supaya Pull Request baru otomatis mengarah ke `develop` dulu.

---

## 2. Persiapan Awal

- Akun GitHub untuk 5 anggota tim
- 1 orang ditentukan sebagai **Pemilik Repo** (dalam kasus ini: `helloprismata-wq`)
- Git terinstall di laptop (cek: `git --version`)
- Laragon terinstall (sudah menyediakan PHP, Composer, dan bisa dipakai buat MySQL lokal)
- Python terinstall (dicek terpisah, tidak otomatis dari Laragon)

---

## 3. Personal Access Token

GitHub tidak lagi menerima password akun biasa untuk operasi Git dari terminal. **Setiap anggota wajib bikin token sendiri-sendiri** (jangan berbagi 1 token untuk semua orang).

**Cara membuat:**
1. Login ke akun GitHub masing-masing
2. Klik foto profil kanan atas → **Settings**
3. Scroll ke bawah sidebar kiri → **Developer settings**
4. **Personal access tokens** → **Tokens (classic)**
5. **Generate new token** → **Generate new token (classic)**
6. Isi:
   - **Note**: bebas, misal `murialo-laptop`
   - **Expiration**: `90 days` atau `No expiration`
   - **Select scopes**: centang **`repo`** (otomatis mencentang semua sub-opsi)
7. **Generate token**
8. **Salin token SEKARANG JUGA** (`ghp_xxxxxxxxxxxx`) — hanya ditampilkan sekali
9. Simpan di catatan pribadi yang aman

> ⚠️ Token = password. Jangan dikirim di grup chat terbuka atau di-commit ke GitHub.

---

## 4. Membuat Repository

Dibuat oleh **Pemilik Repo** (`helloprismata-wq`), sebagai akun pribadi — **tanpa GitHub Organization**.

### Repo 1 — `murialo-web`
| Setting | Nilai |
|---|---|
| Owner | `helloprismata-wq` |
| Repository name | `murialo-web` |
| Description | Web app Murialo — sistem rekrutmen berbasis AI (Laravel 12) |
| Visibility | Private |
| Add README | **On** |
| Add .gitignore | Laravel |
| Add license | Kosong |

### Repo 2 — `murialo-ai-engine`
| Setting | Nilai |
|---|---|
| Owner | `helloprismata-wq` |
| Repository name | `murialo-ai-engine` |
| Description | AI Engine Murialo — 5 modul kecerdasan buatan untuk rekrutmen (Python FastAPI) |
| Visibility | Private |
| Add README | **On** |
| Add .gitignore | Python |
| Add license | Kosong |

> **Penting:** Add README harus **On** di kedua repo. Kalau dimatikan, repo jadi "empty repository" tanpa branch `main` yang valid, dan bikin masalah waktu bikin branch `develop` di langkah berikutnya.

---

## 5. Invite Anggota Tim (Collaborator)

Karena tidak pakai Organization, tiap anggota di-invite langsung sebagai **Collaborator** di masing-masing repo (dilakukan **2 kali** — sekali per repo, meski orangnya sama).

1. Buka repo → **Settings** → **Collaborators**
2. Klik **Add people**
3. Cari username GitHub tiap anggota, tambahkan satu per satu
4. Pilih role **Write**
5. Tiap anggota accept undangan lewat email/notifikasi GitHub
6. Cek status di **Settings → Collaborators** — harus "Collaborator" (bukan "Pending")

**Status akhir tim di kedua repo:**

| Nama / Username | Status |
|---|---|
| Muhammad Adi Pratama (AdiP15) | ✅ Collaborator aktif |
| brobrobro12 | ✅ Collaborator aktif |
| danuljiyad-maker | ✅ Collaborator aktif |
| FajarMuna | ✅ Collaborator aktif |
| Tama / SatyatmaRaka | ✅ Collaborator aktif |

---

## 6. Clone Repository

Dikerjakan **oleh setiap anggota**, di laptop masing-masing.

**Opsi A — `git clone`:**
```bash
git clone https://github.com/helloprismata-wq/murialo-web.git
git clone https://github.com/helloprismata-wq/murialo-ai-engine.git
```
Saat diminta login: **username** = akun sendiri, **password** = Personal Access Token akun sendiri.

**Opsi B — GitHub CLI (`gh`):**
```bash
gh auth status   # pastikan sudah login sebagai akun sendiri
gh repo clone helloprismata-wq/murialo-web
gh repo clone helloprismata-wq/murialo-ai-engine
```

> URL/nama repo selalu sama (`helloprismata-wq/...`) — itu alamat repo, bukan akun yang login. Yang harus pakai identitas sendiri adalah akun yang login dan git config di bawah.

**Set identitas Git** (supaya commit tercatat atas nama sendiri, bukan Pemilik Repo):
```bash
cd murialo-web
git config user.name "Nama Kalian"
git config user.email "email-kalian@gmail.com"

cd ../murialo-ai-engine
git config user.name "Nama Kalian"
git config user.email "email-kalian@gmail.com"
```

---

## 7. Membuat Branch develop dan Branch Fitur

Dikerjakan sekali (oleh siapa saja yang sudah clone dan punya akses Write), untuk kedua repo.

### 7.1 Di `murialo-web`
```bash
cd murialo-web
git checkout -b develop
git push origin develop

git checkout -b fitur/landing-page
git push origin fitur/landing-page
git checkout develop

git checkout -b fitur/auth
git push origin fitur/auth
git checkout develop

git checkout -b fitur/crud-lowongan
git push origin fitur/crud-lowongan
git checkout develop

git checkout -b fitur/upload-cv
git push origin fitur/upload-cv
git checkout develop

git checkout -b fitur/dashboard-hrd
git push origin fitur/dashboard-hrd
git checkout develop
```

### 7.2 Di `murialo-ai-engine`
```bash
cd ../murialo-ai-engine
git checkout -b develop
git push origin develop

git checkout -b fitur/resume-parser
git push origin fitur/resume-parser
git checkout develop

git checkout -b fitur/skill-matching
git push origin fitur/skill-matching
git checkout develop

git checkout -b fitur/rekomendasi-kandidat
git push origin fitur/rekomendasi-kandidat
git checkout develop

git checkout -b fitur/deteksi-anomali
git push origin fitur/deteksi-anomali
git checkout develop

git checkout -b fitur/dashboard-analitik
git push origin fitur/dashboard-analitik
git checkout develop
```

### 7.3 Verifikasi
Cek langsung dari dropdown branch di GitHub (bukan activity feed) — harus muncul 7 branch di tiap repo (`main`, `develop`, + 5 branch fitur).

**Anggota lain yang belum bikin branch** cukup jalankan:
```bash
git branch -a       # lihat semua branch termasuk remote
git checkout fitur/nama-branch-nya   # otomatis nyambung ke branch remote
```

---

## 8. Branch Protection Ruleset

Dibuat **2 ruleset per repo** (jadi total 4 ruleset — 2 di `murialo-web`, 2 di `murialo-ai-engine`), lewat **Settings → Rulesets → New ruleset → New branch ruleset**.

> ⚠️ **Pelajaran penting**: saat membuat target branch, selalu pilih **"Include by pattern"** dan ketik nama branch-nya langsung (`main` atau `develop`). **Jangan** pilih "Include default branch" — opsi itu otomatis "mengikuti" branch mana pun yang jadi default, sehingga kalau default branch diubah di kemudian hari, ruleset ikut salah sasaran.

### 8.1 Ruleset "Protect main"
| Setting | Nilai |
|---|---|
| Ruleset Name | `Protect main` |
| Enforcement status | Active |
| Bypass list | Kosong |
| Target branches | Include by pattern → `main` |

**Rules yang diaktifkan:**
- ✅ Restrict deletions
- ✅ Require a pull request before merging → Required approvals: **1**
- ✅ Dismiss stale pull request approvals when new commits are pushed
- ✅ Require conversation resolution before merging
- ✅ Require an additional approval for unattributed Copilot pull requests (default)
- ✅ Block force pushes

**Rules yang TIDAK diaktifkan** (bisa ditambah nanti kalau sudah pakai CI/CD): Restrict creations/updates, Require linear history, Require deployments to succeed, Require signed commits, Require status checks to pass, Require code scanning/quality results, Restrict code coverage, Automatically request Copilot code review.

### 8.2 Ruleset "Protect develop"
Versi lebih longgar, karena `develop` adalah branch kerja harian tim.

| Setting | Nilai |
|---|---|
| Ruleset Name | `Protect develop` |
| Enforcement status | Active |
| Bypass list | Kosong |
| Target branches | Include by pattern → `develop` |

**Rules yang diaktifkan:**
- ✅ Restrict deletions
- ✅ Require a pull request before merging → Required approvals: **1**
- ✅ Require an additional approval for unattributed Copilot pull requests (default)
- ✅ Block force pushes

**Rules yang TIDAK diaktifkan** (biar iterasi lebih cepat): Dismiss stale approvals, Require review from specific teams/Code Owners, Require conversation resolution before merging, Require status checks to pass, dan lainnya.

### Hasil akhir
- `main` terkunci total — semua perubahan wajib lewat Pull Request + 1 approval, tidak bisa dihapus/force-push
- `develop` tetap fleksibel untuk kerja harian, tapi tetap wajib lewat PR dan tidak bisa dihapus/force-push

---

## 9. Mengubah Default Branch

Default branch diubah dari `main` → `develop` di **kedua repo**, supaya Pull Request baru dari branch fitur otomatis mengarah ke `develop` (bukan langsung ke `main`).

**Cara:** `Settings` → `General` → bagian **Default branch** → klik ikon switch (↔) → pilih `develop` → **Update** → konfirmasi.

✅ Status: sudah diubah di `murialo-web` dan `murialo-ai-engine`

---

## 10. Setup Project murialo-web (Laravel 12)

### 10.1 Kendala: Folder Sudah Berisi File
Karena folder `murialo-web` sudah ada isi (README, `.gitignore` dari GitHub), Composer menolak install langsung ke folder itu (`Project directory is not empty`).

**Solusi** — install ke folder sementara dulu, baru pindahkan:
```powershell
# 1. Keluar dari folder project
cd ..

# 2. Install Laravel ke folder sementara, kunci ke versi 12.x
composer create-project laravel/laravel laravel-temp "12.*"

# 3. Pindahkan seluruh isi (termasuk file tersembunyi) ke murialo-web
robocopy laravel-temp murialo-web /E /MOVE

# 4. Hapus folder sementara yang sudah kosong
Remove-Item -Path laravel-temp -Recurse -Force

# 5. Masuk kembali ke folder project
cd murialo-web
```

> Catatan: `"12.*"` mengunci instalasi ke versi Laravel 12.x (tidak akan naik ke versi 13 meskipun itu versi terbaru saat ini). `.gitignore` bawaan GitHub akan tertimpa `.gitignore` bawaan Laravel — ini aman karena isinya serupa.

### 10.2 Verifikasi Instalasi
```powershell
php artisan --version
```
✅ Hasil: `Laravel Framework 12.69.2`

### 10.3 Setup Environment (.env) & Database
```powershell
# Copy file konfigurasi contoh
cp .env.example .env

# Generate application key (wajib untuk enkripsi session/cookie)
php artisan key:generate
```

**Setup database lokal via Laragon:**
1. Buka Laragon → klik kanan → **Database** → phpMyAdmin (atau HeidiSQL)
2. Buat database baru: `murialo_web`
3. Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=murialo_web
DB_USERNAME=root
DB_PASSWORD=
```

**Jalankan migration:**
```powershell
php artisan migrate
```
✅ Hasil: tabel `users`, `cache`, `jobs` berhasil dibuat.

### 10.4 Menjalankan Project
```powershell
php artisan serve
```
✅ Hasil: `INFO Server running on http://127.0.0.1:8000` — halaman welcome Laravel muncul di browser.

### 10.5 Commit ke develop
```powershell
git add .
git commit -m "init: setup project Laravel dasar"
git push origin develop
```

---

## 11. Setup Project murialo-ai-engine (FastAPI)

### 11.1 Cek Python
```powershell
python --version   # Python 3.13.14
pip --version      # pip 26.1.2
```
> Beda dengan PHP, Python **tidak otomatis** ada di Laragon — perlu dicek/diinstall terpisah.

### 11.2 Bikin Virtual Environment
Virtual environment = "kotak terpisah" untuk library Python khusus project ini, biar tidak bentrok dengan project lain.
```powershell
python -m venv venv
venv\Scripts\activate
```
> Kalau muncul error "execution policy" saat activate:
> ```powershell
> Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
> ```

### 11.3 Install FastAPI + Uvicorn
```powershell
pip install fastapi uvicorn[standard]
pip freeze > requirements.txt
```

### 11.4 Struktur Folder Project
```powershell
mkdir app
mkdir app\routers
mkdir app\models
mkdir app\services
New-Item -ItemType File -Path "app\__init__.py"
New-Item -ItemType File -Path "app\routers\__init__.py"
New-Item -ItemType File -Path "app\models\__init__.py"
New-Item -ItemType File -Path "app\services\__init__.py"
```

Struktur akhir:
```
murialo-ai-engine/
├── venv/                  (tidak di-commit, sudah masuk .gitignore)
├── app/
│   ├── __init__.py
│   ├── routers/           (endpoint API per modul)
│   ├── models/            (struktur data / schema)
│   └── services/          (logika AI/ML tiap modul)
├── main.py                (entry point aplikasi)
└── requirements.txt
```

### 11.5 Bikin main.py dan Router Contoh (resume-parser)

**`main.py`** — entry point aplikasi:
```python
from fastapi import FastAPI
from app.routers import resume_parser

app = FastAPI(
    title="Murialo AI Engine",
    description="AI Engine Murialo — modul kecerdasan buatan untuk rekrutmen",
    version="1.0.0"
)

app.include_router(resume_parser.router)

@app.get("/")
def read_root():
    return {"message": "Murialo AI Engine is running"}

@app.get("/health")
def health_check():
    return {"status": "ok"}
```

**`app/routers/resume_parser.py`** — contoh router modul (pola ini dicontoh untuk 4 modul lain: skill-matching, rekomendasi-kandidat, deteksi-anomali, dashboard-analitik):
```python
from fastapi import APIRouter

router = APIRouter(prefix="/resume-parser", tags=["Resume Parser"])

@router.get("/")
def resume_parser_status():
    return {"module": "resume-parser", "status": "ready"}
```

### 11.6 Menjalankan & Tes Server
```powershell
uvicorn main:app --reload
```
✅ Hasil: server jalan di `http://127.0.0.1:8000`

Buka browser ke `http://127.0.0.1:8000/docs` — halaman **Swagger UI** muncul dengan endpoint `/`, `/health`, dan `/resume-parser/` siap dicoba langsung dari situ.

### 11.7 Koneksi Database (Shared dengan murialo-web)

**Keputusan arsitektur:** `murialo-ai-engine` memakai **database MySQL yang sama** dengan `murialo-web` (`murialo_web`), bukan database terpisah.

**Alasan:**
- Data saling terkait (AI engine perlu baca data CV, lowongan, pelamar yang di-input lewat `murialo-web`)
- Menghindari data ganda/tidak sinkron antar 2 database
- Lebih simpel untuk tim kecil di tahap awal — tidak perlu bikin API layer penghubung dulu
- Bisa dipisah nanti kalau project sudah besar, migrasinya lebih mudah dibanding sebaliknya

> Konsekuensi: setiap anggota yang menjalankan `murialo-ai-engine` di laptopnya **wajib juga sudah setup database `murialo_web`** (lihat Bagian 10.3).

**1. Install library database:**
```powershell
cd murialo-ai-engine
venv\Scripts\activate
pip install sqlalchemy pymysql
pip freeze > requirements.txt
```

**2. Bikin file `.env`:**
```env
DATABASE_URL=mysql+pymysql://root:@127.0.0.1:3306/murialo_web
```

**3. Bikin `app/database.py`** (setup koneksi & session SQLAlchemy):
```python
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker, declarative_base
from dotenv import load_dotenv
import os

load_dotenv()

DATABASE_URL = os.getenv("DATABASE_URL")

engine = create_engine(DATABASE_URL)
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()

def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
```

**4. Contoh model** yang merefer ke tabel `users` bawaan Laravel (`app/models/user.py`):
```python
from sqlalchemy import Column, Integer, String
from app.database import Base

class User(Base):
    __tablename__ = "users"

    id = Column(Integer, primary_key=True, index=True)
    name = Column(String(255))
    email = Column(String(255), unique=True)
```

> Pola ini yang dicontoh untuk model-model lain sesuai kebutuhan tiap modul AI (resume, lowongan, hasil analisis, dll).

### 11.8 Commit ke develop
```powershell
# Pastikan venv/ masuk .gitignore
Add-Content -Path .gitignore -Value "venv/"

git add .
git commit -m "init: setup project FastAPI dasar dengan modul resume-parser"
git push origin develop
```

---

## 12. Tabel Pembagian Branch per Orang

| Nama | Branch di `murialo-ai-engine` | Branch di `murialo-web` |
|---|---|---|
| Khamdanul Jiyad | `fitur/resume-parser` | `fitur/upload-cv` |
| Muhammad Adi Pratama | `fitur/skill-matching` | `fitur/crud-lowongan` |
| Satyatma Raka Wiratama | `fitur/rekomendasi-kandidat` | `fitur/dashboard-hrd` |
| Ahmad Sulthon Fajar Nailul Muna | `fitur/deteksi-anomali` | `fitur/auth` |
| Habib Hadi Widjanarko | `fitur/dashboard-analitik` | `fitur/landing-page` |

---

## 13. Workflow Kerja Harian

Alur yang harus diikuti **setiap kali** mulai kerja. Bagian ini dijelaskan detail per langkah, termasuk **apa yang terjadi di balik layar** supaya semua anggota paham, bukan cuma hafal perintahnya.

### Langkah 1 — Sinkron dari develop dulu

```bash
git checkout develop
git pull origin develop
git checkout fitur/nama-branch-nya-sendiri
git merge develop
```

**Kenapa ini penting:** `develop` terus berubah karena banyak orang gabungin kerjaan ke situ. Kalau branch fitur kamu tidak disinkron, kamu bisa kerja di atas kode yang sudah usang, dan nanti pas Pull Request malah muncul konflik besar yang susah dibereskan. Menyamakan dulu di awal jauh lebih gampang daripada membereskan konflik di akhir.

### Langkah 2 — Ngoding seperti biasa, lalu commit

```bash
git add .
git commit -m "fitur: deskripsi singkat perubahan"
```

- `git add .` menandai semua file yang berubah supaya ikut disimpan
- `git commit -m "..."` menyimpan perubahan itu sebagai satu "titik checkpoint" di riwayat, dengan pesan yang jelas (contoh: `feat: tambah endpoint login`)

### Langkah 3 — Push ke GitHub

```bash
git push origin fitur/nama-branch-nya-sendiri
```

Ini mengirim checkpoint tadi ke GitHub, **ke branch fitur milikmu** — bukan ke `develop` atau `main` (itu diblokir ruleset, lihat catatan penting di bawah).

### Langkah 4 — Buat Pull Request (PR) di GitHub

Setelah push, GitHub biasanya langsung menampilkan notifikasi hijau dengan tombol **"Compare & pull request"** di halaman utama repo. Kalau tidak muncul, bisa juga lewat tab **Pull requests** → **New pull request**.

1. Pastikan **base: `develop`** ← **compare: `fitur/nama-branch-nya`** (jangan sampai base-nya `main`)
2. Judul PR biasanya sudah otomatis terisi dari pesan commit
3. Isi **deskripsi** — kebiasaan yang baik adalah menjelaskan apa yang berubah dan cara mengetesnya, contoh:
   ```markdown
   ## Perubahan
   - Tambah endpoint GET /users/
   - Setup koneksi database

   ## Cara tes
   1. Jalankan `uvicorn main:app --reload`
   2. Buka /docs, coba endpoint terkait
   3. Pastikan tidak error
   ```
4. Klik **"Create pull request"**

Setelah PR terbuat, di sidebar kanan akan muncul status **"No reviews—at least 1 approving review is required"** — ini normal, sesuai ruleset yang sudah di-setting (Bagian 8).

### Langkah 5 — Minta review dari rekan tim

1. Kirim link PR ke salah satu rekan tim (lewat chat/grup)
2. Rekan tim membuka link, klik tab **"Files changed"** untuk melihat kode yang berubah
3. Klik **"Review changes"** → pilih **"Approve"** → **"Submit review"**

Setelah approval masuk, status di PR berubah jadi **"Changes approved"**, dan tombol **"Merge pull request"** yang tadinya abu-abu (tidak bisa diklik) berubah jadi **hijau dan aktif**.

### Langkah 6 — Merge

- Yang klik **"Merge pull request"** adalah **pembuat PR itu sendiri** (bukan yang me-review), setelah syarat approval terpenuhi
- Klik **"Merge pull request"** → akan muncul kotak konfirmasi pesan commit → klik **"Confirm merge"**
- Setelah berhasil, GitHub menampilkan **"Pull request successfully merged and closed"**
- Muncul juga opsi **"Delete branch"** — aman diklik, ini cuma menghapus branch fitur di GitHub (remote), branch lokal di laptop tidak ikut terhapus. Kalau nanti mau lanjut kerja di modul yang sama, tinggal bikin ulang branch dari `develop` terbaru.

### Langkah 7 — Rilis ke production (nanti, kalau develop sudah stabil)

Buat PR baru dari `develop` → `main`, review sesuai ruleset `main` (lebih ketat: butuh conversation resolution, dismiss stale approval, dll — lihat Bagian 8.1), lalu merge.

### Contoh Nyata: PR Pertama yang Berhasil (Referensi)

Sebagai gambaran konkret, ini yang terjadi saat PR pertama (`#3` di `murialo-ai-engine`, isinya setup koneksi database + endpoint `/users/`) dikerjakan:

1. Raka coba `git push origin develop` langsung → **ditolak** oleh GitHub dengan pesan `GH013: Repository rule violations found... Changes must be made through a pull request.` — ini **bukan error**, melainkan bukti ruleset `Protect develop` bekerja sesuai rencana.
2. Raka pindah ke branch `fitur/rekomendasi-kandidat`, `git merge develop`, lalu `git push origin fitur/rekomendasi-kandidat` — berhasil, karena branch fitur tidak diproteksi seketat `develop`.
3. Raka buka GitHub, klik "Compare & pull request", isi deskripsi, klik "Create pull request" → PR #3 terbuat.
4. Raka kirim link PR ke Adi lewat chat tim.
5. Adi buka link, cek perubahan, klik Approve.
6. Raka kembali ke halaman PR, tombol Merge sudah aktif, klik "Merge pull request" → "Confirm merge".
7. PR berstatus **"Merged"**, kode resmi masuk ke `develop`.

Pola ini yang akan berulang untuk **setiap** perubahan ke `develop` maupun `main`, oleh siapa pun di tim.

### Aturan Wajib
- 🚫 Jangan push langsung ke `main` atau `develop` — semua wajib lewat Pull Request (ruleset akan otomatis menolak kalau dicoba)
- 🚫 Jangan coba force push — sudah diblokir ruleset
- ✅ Selalu `git pull origin develop` sebelum mulai kerja baru
- ✅ Siapa pun boleh klik "Merge" asal dia yang bikin PR-nya dan syarat approval sudah terpenuhi

---

## 14. Tracking Tugas dengan GitHub Projects

Supaya progress 5 anggota tim yang kerja paralel tetap terpantau tanpa harus tanya-tanya di chat, dipakai **GitHub Projects** — papan kanban sederhana (To Do / In Progress / Done) yang terhubung ke kedua repo.

### 14.1 Bikin Project Baru
1. Buka `github.com/helloprismata-wq` (halaman profil)
2. Tab **Projects** → **New project**
3. Pilih template **Board**
4. Nama: `Murialo Development`
5. **Create**

### 14.2 Hubungkan ke Kedua Repo
1. Di halaman project, klik **⚙️ Settings**
2. Bagian **Repositories** → **Add repository**
3. Tambahkan `murialo-web` dan `murialo-ai-engine` — supaya Issues dari kedua repo tampil di satu board yang sama

### 14.3 Bikin Issue untuk Tiap Fitur
Untuk **setiap branch fitur** (10 total — 5 di tiap repo), bikin 1 Issue:
1. Buka repo terkait → tab **Issues** → **New issue**
2. Judul: nama fitur, contoh `Fitur: Auth (Login & Register)`
3. Deskripsi singkat + nama branch terkait (`fitur/auth`)
4. **Assignees**: pilih penanggung jawab sesuai tabel Bagian 12
5. **Create**

### 14.4 Masukkan Issue ke Board
1. Balik ke halaman Project
2. **+ Add item** → cari dan pilih tiap Issue yang sudah dibuat
3. Ulangi sampai semua 10 Issue masuk — otomatis ke kolom **To Do**

### 14.5 Cara Pakai Sehari-hari
- Mulai kerja fitur → pindahkan card ke **In Progress** (drag & drop)
- PR sudah di-merge ke `develop` → pindahkan ke **Done**
- Semua anggota bisa cek status kapan saja tanpa perlu bertanya di chat grup

---

## 15. Troubleshooting

| Error | Penyebab | Solusi |
|---|---|---|
| `403 Write access to repository not granted` | Token belum dibuat/kedaluwarsa, salah akun, atau belum jadi Collaborator | Buat ulang token, cek scope `repo`, cek status Collaborator |
| `remote: Repository not found` | Belum di-invite / belum accept, atau repo private tanpa autentikasi yang benar | Cek Settings → Collaborators, pastikan bukan "Pending" |
| `git branch` cuma nampilin `main` setelah clone | Wajar — itu cuma branch lokal. Branch lain sudah ada di remote | Pakai `git branch -a` untuk lihat semua, lalu `git checkout nama-branch` |
| `cd` bilang folder tidak ketemu | Sudah berada di dalam folder itu | Cek `pwd` (Mac/Linux) atau lihat prompt terminal |
| Diminta password terus padahal sudah pakai token | Credential lama tersimpan salah | Hapus di Credential Manager (Windows) → Windows Credentials → `git:https://github.com` |
| Branch tidak muncul di GitHub padahal `git checkout -b` sukses | Baru dibuat di laptop, belum di-push | `git push origin nama-branch`, atau `git push origin --all` |
| `composer create-project` gagal: "Project directory is not empty" | Folder sudah ada isi dari GitHub (README, .gitignore) | Install ke folder sementara dulu, lalu `robocopy` ke folder asli (lihat Bagian 10.1) |
| Ruleset "Protect main" ternyata melindungi `develop` | Target branch dibuat dengan "Include default branch" yang otomatis ikut pindah saat default branch diubah | Edit ruleset, ganti target jadi "Include by pattern" dan ketik `main` secara manual |

---

## 16. Status Akhir & Checklist

| # | Task | murialo-web | murialo-ai-engine |
|---|---|---|---|
| 1 | Repo dibuat, Private, README on | ✅ | ✅ |
| 2 | 5 anggota jadi Collaborator (accept) | ✅ | ✅ |
| 3 | Clone + git config di laptop masing-masing | ✅ | ✅ |
| 4 | Branch develop + 5 branch fitur | ✅ | ✅ |
| 5 | Ruleset Protect main | ✅ | ✅ |
| 6 | Ruleset Protect develop | ✅ | ✅ |
| 7 | Default branch → develop | ✅ | ✅ |
| 8 | Project dasar terinstall & jalan | ✅ Laravel 12.69.2 | ✅ FastAPI 0.141.1 |
| 9 | Database/environment siap | ✅ MySQL + migration | — (belum perlu di tahap ini) |
| 10 | Commit awal ter-push ke develop | ✅ | ✅ |
| 11 | Koneksi database shared (murialo_web) di FastAPI | — (sudah jadi sumber data) | ✅ SQLAlchemy + PyMySQL |
| 12 | Alur Pull Request + review + merge sudah dites nyata | — | ✅ PR #3 berhasil merge |

**🎯 Kesimpulan:** Kedua project sudah punya fondasi lengkap — kode jalan, Git & GitHub rapi dengan branch protection **yang sudah terbukti bekerja** (menolak push langsung ke `develop`), database `murialo-ai-engine` sudah tersambung ke database yang sama dengan `murialo-web`, dan seluruh tim sudah punya akses penuh (Collaborator aktif, bukan Pending). Alur kerja Pull Request → review → merge juga **sudah dites end-to-end dan berhasil** (PR #3, direview oleh Adi, di-merge oleh Raka).

**Tim siap mulai coding fitur sungguhan** sesuai pembagian branch di Bagian 12, mengikuti pola workflow yang sudah terbukti di Bagian 13.

---

## 17. Langkah Selanjutnya

Setup infrastruktur sudah selesai 100%. Ini urutan yang disarankan untuk fase berikutnya:

### 17.1 Sebarkan Dokumentasi ke Tim (lakukan segera)
Kirim file `.md` ini atau link artifact-nya ke grup chat tim, supaya semua anggota (bukan cuma Pemilik Repo) paham alur clone, branch, PR, dan aturan kerja. Ini mencegah pertanyaan berulang yang jawabannya sudah ada di sini.

### 17.2 Setup GitHub Projects (lakukan sebelum mulai coding rame-rame)
Ikuti langkah di Bagian 14 — bikin board, hubungkan ke kedua repo, buat 10 Issue (1 per branch fitur), assign ke masing-masing anggota. Tanpa ini, progress 5 orang yang kerja paralel gampang jadi tidak terpantau.

### 17.3 Urutan Prioritas Fitur yang Disarankan

**`murialo-web`** — beberapa fitur saling bergantung, sarannya:
1. **`fitur/auth`** (Sulthon) — dikerjakan **paling awal**. Hampir semua fitur lain butuh sistem login/session dulu sebelum bisa jalan.
2. **`fitur/landing-page`** (Habib) — bisa paralel dari awal, tidak bergantung fitur lain.
3. **`fitur/crud-lowongan`** (Adi) — data lowongan dibutuhkan modul AI (skill-matching, rekomendasi-kandidat).
4. **`fitur/upload-cv`** (Khamdanul) — data CV dibutuhkan modul AI (resume-parser).
5. **`fitur/dashboard-hrd`** (Raka) — biasanya dikerjakan belakangan karena menampilkan gabungan data dari fitur-fitur lain.

**`murialo-ai-engine`** — 5 modulnya relatif independen satu sama lain (resume-parser, skill-matching, rekomendasi-kandidat, deteksi-anomali, dashboard-analitik), jadi semua anggota bisa mulai bersamaan begitu data dasarnya (lowongan, CV) mulai tersedia dari `murialo-web`.

### 17.4 Mulai Coding dengan Pola yang Sudah Ada
Untuk tiap fitur baru, contoh pola yang sudah terbukti jalan (dari PR #3):
1. `git checkout develop && git pull origin develop`
2. `git checkout fitur/nama-branch-nya && git merge develop`
3. Ngoding — untuk `murialo-ai-engine`, contoh router (`resume_parser.py`), model (`user.py`), dan koneksi database (`database.py`) sudah ada sebagai referensi
4. Commit → push ke branch fitur → buat PR ke `develop` → minta review → merge (Bagian 13)
5. Update status Issue di GitHub Projects dari **To Do** → **In Progress** → **Done**

### 17.5 Hal yang Ditunda Dulu (Belum Perlu Sekarang)
- **Database production** (server sungguhan, bukan lokal) — baru relevan kalau aplikasi sudah punya fitur jalan dan siap di-deploy
- **CI/CD otomatis** (status checks, code scanning) — bisa ditambahkan ke ruleset nanti kalau tim sudah terbiasa dengan alur PR manual
- **Pemisahan database** `murialo-web` dan `murialo-ai-engine` — baru dipertimbangkan kalau skala project sudah besar (lihat diskusi trade-off di riwayat keputusan arsitektur)

### 17.6 Checklist Singkat untuk Minggu Ini
- [ ] Dokumentasi sudah dibagikan ke semua anggota
- [ ] GitHub Projects board sudah dibuat dan terisi 10 Issue
- [ ] `fitur/auth` mulai dikerjakan
- [ ] Minimal 1 PR baru (selain PR #3) berhasil dibuat dan di-merge oleh anggota lain, sebagai bukti semua orang paham alurnya, bukan cuma Raka

---

*Dokumen ini dibuat sebagai panduan onboarding tim Murialo. Update dokumen ini jika ada perubahan struktur branch, ruleset, atau workflow di kemudian hari.*
