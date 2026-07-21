<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PohonKinerjaSeeder extends Seeder
{
    public function run(): void
    {
        $username = 'admin';
        $now = now();

        $satuan = [
            ['id' => $s1 = Str::uuid(), 'satuan' => 'Persen (%)', 'keterangan' => 'Persentase', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $s2 = Str::uuid(), 'satuan' => 'Nilai', 'keterangan' => 'Nilai Absolut', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $s3 = Str::uuid(), 'satuan' => 'Indeks', 'keterangan' => 'Indeks', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $s4 = Str::uuid(), 'satuan' => 'Poin', 'keterangan' => 'Skor/Poin', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $s5 = Str::uuid(), 'satuan' => 'Rupiah', 'keterangan' => 'Mata Uang', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('master_satuan')->insert($satuan);

        $visiId = Str::uuid();
        DB::table('pohon_kinerja_visi')->insert([
            'id' => $visiId,
            'period_starts' => 2024,
            'period_ends' => 2031,
            'visi' => 'Terwujudnya Kabupaten Pasaman yang Maju, Mandiri, dan Berdaya Saing Berbasis Agribisnis dan Pariwisata',
            'is_active' => true,
            'created_by' => $username,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $misi = [
            ['id' => $m1 = Str::uuid(), 'pohon_kinerja_visi_id' => $visiId, 'order' => 1, 'misi' => 'Mewujudkan tata kelola pemerintahan yang bersih, efektif, efisien, dan melayani', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $m2 = Str::uuid(), 'pohon_kinerja_visi_id' => $visiId, 'order' => 2, 'misi' => 'Meningkatkan kualitas sumber daya manusia yang berdaya saing', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $m3 = Str::uuid(), 'pohon_kinerja_visi_id' => $visiId, 'order' => 3, 'misi' => 'Meningkatkan pembangunan infrastruktur yang merata dan berkualitas', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $m4 = Str::uuid(), 'pohon_kinerja_visi_id' => $visiId, 'order' => 4, 'misi' => 'Mewujudkan kemandirian ekonomi berbasis agribisnis, pariwisata, dan ekonomi kreatif', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $m5 = Str::uuid(), 'pohon_kinerja_visi_id' => $visiId, 'order' => 5, 'misi' => 'Meningkatkan kehidupan sosial kemasyarakatan yang harmonis dan berbudaya', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('pohon_kinerja_misi')->insert($misi);

        $tujuan = [
            ['id' => $t1 = Str::uuid(), 'pohon_kinerja_misi_id' => $m1, 'order' => 1, 'tujuan' => 'Meningkatnya akuntabilitas kinerja pemerintah daerah', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $t2 = Str::uuid(), 'pohon_kinerja_misi_id' => $m1, 'order' => 2, 'tujuan' => 'Meningkatnya kualitas pelayanan publik berbasis digital', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $t3 = Str::uuid(), 'pohon_kinerja_misi_id' => $m2, 'order' => 1, 'tujuan' => 'Meningkatnya akses dan kualitas pendidikan', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $t4 = Str::uuid(), 'pohon_kinerja_misi_id' => $m2, 'order' => 2, 'tujuan' => 'Meningkatnya derajat kesehatan masyarakat', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $t5 = Str::uuid(), 'pohon_kinerja_misi_id' => $m3, 'order' => 1, 'tujuan' => 'Meningkatnya konektivitas antar wilayah', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $t6 = Str::uuid(), 'pohon_kinerja_misi_id' => $m3, 'order' => 2, 'tujuan' => 'Meningkatnya akses masyarakat terhadap infrastruktur dasar', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $t7 = Str::uuid(), 'pohon_kinerja_misi_id' => $m4, 'order' => 1, 'tujuan' => 'Meningkatnya produktivitas sektor pertanian dan agribisnis', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $t8 = Str::uuid(), 'pohon_kinerja_misi_id' => $m4, 'order' => 2, 'tujuan' => 'Meningkatnya kunjungan wisatawan dan ekonomi kreatif', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $t9 = Str::uuid(), 'pohon_kinerja_misi_id' => $m5, 'order' => 1, 'tujuan' => 'Meningkatnya partisipasi masyarakat dalam pembangunan', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $t10 = Str::uuid(), 'pohon_kinerja_misi_id' => $m5, 'order' => 2, 'tujuan' => 'Meningkatnya pelestarian nilai-nilai budaya dan kearifan lokal', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('pohon_kinerja_tujuan')->insert($tujuan);

        $sasaran = [
            ['id' => $ss1 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t1, 'order' => 1, 'sasaran' => 'Meningkatnya nilai SAKIP Kabupaten Pasaman', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss2 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t1, 'order' => 2, 'sasaran' => 'Meningkatnya kapasitas aparatur dalam pengelolaan kinerja', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss3 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t2, 'order' => 1, 'sasaran' => 'Meningkatnya indeks kepuasan masyarakat', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss4 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t2, 'order' => 2, 'sasaran' => 'Terintegrasinya layanan publik berbasis digital', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss5 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t3, 'order' => 1, 'sasaran' => 'Meningkatnya angka rata-rata lama sekolah', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss6 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t3, 'order' => 2, 'sasaran' => 'Meningkatnya angka partisipasi sekolah', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss7 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t4, 'order' => 1, 'sasaran' => 'Meningkatnya angka harapan hidup', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss8 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t4, 'order' => 2, 'sasaran' => 'Menurunnya angka stunting', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss9 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t5, 'order' => 1, 'sasaran' => 'Meningkatnya kualitas jalan kabupaten', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss10 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t5, 'order' => 2, 'sasaran' => 'Meningkatnya aksesibilitas wilayah terisolir', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss11 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t6, 'order' => 1, 'sasaran' => 'Meningkatnya akses air bersih dan sanitasi layak', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss12 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t7, 'order' => 1, 'sasaran' => 'Meningkatnya produksi komoditas unggulan daerah', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss13 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t8, 'order' => 1, 'sasaran' => 'Meningkatnya jumlah kunjungan wisatawan domestik dan mancanegara', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss14 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t9, 'order' => 1, 'sasaran' => 'Meningkatnya partisipasi pemuda dan organisasi kemasyarakatan', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $ss15 = Str::uuid(), 'pohon_kinerja_tujuan_id' => $t10, 'order' => 1, 'sasaran' => 'Meningkatnya jumlah event budaya daerah', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('pohon_kinerja_sasaran')->insert($sasaran);

        $indikator = [
            ['id' => Str::uuid(), 'pohon_kinerja_tujuan_id' => $t1, 'pohon_kinerja_sasaran_id' => $ss1, 'order' => 1, 'indikator' => 'Nilai SAKIP', 'defenisi' => 'Nilai Sistem Akuntabilitas Kinerja Instansi Pemerintah', 'pohon_kinerja_satuan_id' => $s3, 'satuan_id' => $s3, 'baseline' => '65', 'target_1' => '68', 'target_2' => '70', 'target_3' => '73', 'target_4' => '75', 'target_5' => '78', 'target_6' => '80', 'is_active' => true, 'is_indikator_kinerja_utama' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid(), 'pohon_kinerja_tujuan_id' => $t1, 'pohon_kinerja_sasaran_id' => $ss2, 'order' => 1, 'indikator' => 'Persentase OPD dengan nilai SAKIP minimal BB', 'defenisi' => 'Persentase perangkat daerah yang mencapai nilai SAKIP minimal BB', 'pohon_kinerja_satuan_id' => $s1, 'satuan_id' => $s1, 'baseline' => '30', 'target_1' => '40', 'target_2' => '50', 'target_3' => '60', 'target_4' => '70', 'target_5' => '80', 'target_6' => '90', 'is_active' => true, 'is_indikator_kinerja_utama' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid(), 'pohon_kinerja_tujuan_id' => $t2, 'pohon_kinerja_sasaran_id' => $ss3, 'order' => 1, 'indikator' => 'Indeks Kepuasan Masyarakat (IKM)', 'defenisi' => 'Nilai indeks kepuasan masyarakat terhadap layanan publik', 'pohon_kinerja_satuan_id' => $s3, 'satuan_id' => $s3, 'baseline' => '78', 'target_1' => '80', 'target_2' => '82', 'target_3' => '84', 'target_4' => '85', 'target_5' => '87', 'target_6' => '88', 'is_active' => true, 'is_indikator_kinerja_utama' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid(), 'pohon_kinerja_tujuan_id' => $t3, 'pohon_kinerja_sasaran_id' => $ss5, 'order' => 1, 'indikator' => 'Rata-rata Lama Sekolah', 'defenisi' => 'Rata-rata lama sekolah penduduk usia 25 tahun ke atas', 'pohon_kinerja_satuan_id' => $s2, 'satuan_id' => $s2, 'baseline' => '8,5', 'target_1' => '9,0', 'target_2' => '9,5', 'target_3' => '10,0', 'target_4' => '10,5', 'target_5' => '11,0', 'target_6' => '11,5', 'is_active' => true, 'is_indikator_kinerja_utama' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid(), 'pohon_kinerja_tujuan_id' => $t4, 'pohon_kinerja_sasaran_id' => $ss7, 'order' => 1, 'indikator' => 'Angka Harapan Hidup', 'defenisi' => 'Rata-rata perkiraan lama hidup penduduk', 'pohon_kinerja_satuan_id' => $s2, 'satuan_id' => $s2, 'baseline' => '70,5', 'target_1' => '71,0', 'target_2' => '71,5', 'target_3' => '72,0', 'target_4' => '72,5', 'target_5' => '73,0', 'target_6' => '73,5', 'is_active' => true, 'is_indikator_kinerja_utama' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid(), 'pohon_kinerja_tujuan_id' => $t4, 'pohon_kinerja_sasaran_id' => $ss8, 'order' => 1, 'indikator' => 'Prevalensi Stunting', 'defenisi' => 'Persentase balita dengan kondisi stunting', 'pohon_kinerja_satuan_id' => $s1, 'satuan_id' => $s1, 'baseline' => '25', 'target_1' => '22', 'target_2' => '19', 'target_3' => '16', 'target_4' => '14', 'target_5' => '12', 'target_6' => '10', 'is_active' => true, 'is_indikator_kinerja_utama' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid(), 'pohon_kinerja_tujuan_id' => $t7, 'pohon_kinerja_sasaran_id' => $ss12, 'order' => 1, 'indikator' => 'PDRB Sektor Pertanian', 'defenisi' => 'Produk Domestik Regional Bruto sektor pertanian', 'pohon_kinerja_satuan_id' => $s5, 'satuan_id' => $s5, 'baseline' => '2,5T', 'target_1' => '2,8T', 'target_2' => '3,0T', 'target_3' => '3,3T', 'target_4' => '3,5T', 'target_5' => '3,8T', 'target_6' => '4,0T', 'is_active' => true, 'is_indikator_kinerja_utama' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('pohon_kinerja_indikator')->insert($indikator);
    }
}
