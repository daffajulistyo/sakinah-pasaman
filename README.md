# SAKINAH Pasamankab — Full Application

Aplikasi Sistem Akuntabilitas Kinerja Pemerintah Kabupaten Pasaman.

**Versi mandiri** — tanpa integrasi eksternal, React + Laravel dalam 1 project.

## Struktur

```
sakinah-full/                     ← 1 project Laravel + React
├── app/                          ← backend controllers & models
├── routes/
│   ├── api.php                   ← API routes
│   └── web.php                   ← web routes + SPA fallback
├── database/                     ← migrations & seeders
├── resources/
│   ├── js/                       ← ⭐ React frontend
│   │   ├── src/                  (pages, redux, api, components)
│   │   ├── public/               (logo, env-config.js)
│   │   ├── index.html
│   │   ├── package.json
│   │   └── vite.config.js
│   └── views/                    ← 18 PDF Blade templates
├── public/                       ← Laravel public + React build output
└── composer.json
```

## Cara Menjalankan

### Development

```bash
# Terminal 1 — Laravel API
php artisan serve --port=8000

# Terminal 2 — React dev server (hot reload)
cd resources/js
pnpm install
npx vite
# akses: http://localhost:5173
# proxy API ke :8000 otomatis
```

### Production Build

```bash
cd resources/js
npx vite build          # output → public/
php artisan serve --port=8000
# akses: http://localhost:8000
```

## Database

PostgreSQL — database `sakipnah` di `localhost:5432`.

```bash
php artisan migrate
php artisan db:seed --class=RefSeeder
```

## Akun Default

### Admin
- **URL:** http://localhost:5173/admin
- **Username:** `admin`
- **Password:** `admin123`
- **Role:** Admin_KDH

### Pegawai
- **URL:** http://localhost:5173/pegawai
- **NIP:** NIP pegawai yg sudah ditambahkan
- **Password:** (sesuai yg diset saat create pegawai)

## API Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/auth` | Login admin |
| POST | `/api/v1/pegawai/auth` | Login pegawai |
| GET | `/api/v1/me` | Verify token |
| GET | `/api/v1/master/pegawai/list` | List pegawai |
| POST | `/api/v1/master/pegawai` | Create pegawai |
| PUT | `/api/v1/master/pegawai/:id` | Update pegawai |
| DELETE | `/api/v1/master/pegawai/:id` | Nonaktifkan pegawai |
| GET | `/api/v1/master/ref/eselon` | Referensi eselon |
| GET | `/api/v1/master/ref/golongan` | Referensi golongan |
| GET | `/api/v1/master/ref/jenis-jabatan` | Referensi jenis jabatan |
| GET | `/api/v1/master/ref/jabatan` | Referensi jabatan |
| GET | `/api/v1/master/ref/sub-opd` | Referensi sub OPD |

## Perbedaan dari Versi Sebelumnya

1. **Tanpa SSO Keycloak** — login langsung via username/password
2. **Tanpa SIMPEG / IKD / SIMONEV / BKN / Madani** — semua data lokal
3. **Modul Pegawai Mandiri** — CRUD pegawai + tabel referensi
4. **Monorepo** — React di `resources/js/`, 1 server production

## Tabel Baru (6)

- `ref_eselon` — referensi eselon
- `ref_golongan` — referensi golongan & pangkat
- `ref_jenis_jabatan` — jenis jabatan
- `ref_jabatan` — nama jabatan
- `ref_sub_opd` — sub OPD
- `pegawai` — data pegawai (ganti tabel user_simpeg)
