<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Realisasi Rencana Aksi OPD</title>
<style>
    /* Page */
    @page {
        margin: 8mm 6mm; /* sangat kecil untuk maksimal area */
    }

    html, body {
        font-family: "DejaVu Sans", "Helvetica", Arial, sans-serif; /* DejaVu mendukung unicode */
        font-size: 9px; /* baseline kecil; bisa 8px jika perlu */
        color: #111;
    }

    /* Overall table */
    table.report {
        width: 100%;
        border-collapse: collapse;
        /* table-layout: fixed; kunci supaya kolom mengikuti lebar yang ditentukan */
        word-wrap: break-word; /* agar teks panjang membungkus */
        font-size: 9px;
    }

    /* Header styling */
    thead th {
        background: #f5f5f5;
        font-weight: 700;
        padding: 4px 6px;
        border: 1px solid #bbb;
        font-size: 8.5px;
    }

    /* Cells */
    td {
        border: 1px solid #ccc;
        padding: 4px 6px; /* kecilkan padding */
        vertical-align: top;
        font-size: 8.5px;
        line-height: 1.1;
    }
    .indent { display:inline-block; }
    /* hindari pemotongan baris pada dompdf (sebagian didukung) */
    tr { page-break-inside: avoid; }
    /* sesuaikan ukuran kolom */
    .col-no { width:15px; }
    .col-name { width:300px; }
    .col-code { width:120px; }
</style>
</head>
<body>
    <h2 style="text-align:center;">Realisasi Rencana Aksi OPD</h2>
    <h2 style="text-align:center;">{{ $data['opd']['nama_opd'] }}</h2>
    <h23style="text-align:center;">TAHUN {{ $data['tahun'] }} </h3>
    <p>Generated at: {{ $data['generated_at'] }}</p>

    <table class="report">
        <thead>
            <!-- header baris 1 (lebih kompleks) -->
            <tr>
                <th class="col-no" rowspan="2">No</th>
                <th rowspan="2">Tujuan/Sasaran</th>
                <th class="col-code" rowspan="2">Indikator Kinerja</th>
                <th colspan="3">Tw1</th>
                <th colspan="3">Tw2</th>
                <th colspan="3">Tw3</th>
                <th colspan="3">Tw4</th>
                <th class="col-code" rowspan="2">Langkah-langkah Pencapaian Target</th>
                <th class="col-code" rowspan="2">Anggaran</th>
                <th class="col-code" rowspan="2">Hambatan</th>
                <th class="col-code" rowspan="2">Tindak Lanjut</th>
            </tr>
            <!-- header baris 2 -->
            <tr>
                <th>T</th>
                <th>R</th>
                <th>C</th>
                <th>T</th>
                <th>R</th>
                <th>C</th>
                <th>T</th>
                <th>R</th>
                <th>C</th>
                <th>T</th>
                <th>R</th>
                <th>C</th>
            </tr>
        </thead>

        <tbody>
            @if($data['realisasi_renaksi']->count() > 0)
            @php $i = 1; @endphp
            @foreach($data['realisasi_renaksi'] as $node)
                <tr>
                    <td rowspan="{{ optional($node['indikator_sasaran'] ?? null)->count() ?? 1 }}" style="text-align: right">{{ $i }}</td>
                    <td rowspan="{{ optional($node['indikator_sasaran'] ?? null)->count() ?? 1 }}">{{ $node['sasaran'] }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['indikator'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['target_tw1'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['realisasi_tw1'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['capaian_tw1'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['target_tw2'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['realisasi_tw2'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['capaian_tw2'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['target_tw3'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['realisasi_tw3'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['capaian_tw3'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['target_tw4'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['realisasi_tw4'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['capaian_tw4'] ?? '' }}</td>
                    <td>
                        @if($node['indikator_sasaran'][0]['langkah']->count() > 0)
                                @php $y=0; @endphp
                                @foreach($node['indikator_sasaran'][0]['langkah'] as $langkah)
                                @php $y++; @endphp
                                <ol>
                                    <li> {{ $langkah['langkah'] }}
                                </ol>
                                @endforeach
                        @else
                            <p> - </p>
                        @endif
                    </td>
                    <td>Rp. {{ number_format($node['anggaran_perjanjian_kinerja']['murni'], 0, ",", ".") ?? 0 }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['hambatan'] ?? '' }}</td>
                    <td>{{ @$node['indikator_sasaran'][0]['rencana_aksi']['tindak_lanjut'] ?? '' }}</td>
                </tr>
                @if($node['indikator_sasaran']->count() > 1)
                @php $n = 1;  @endphp
                @foreach($node['indikator_sasaran'] as $ind)
                @if($n == 2)
                <tr>
                    <td>{{ @$ind['indikator'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['target_tw1'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['realisasi_tw1'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['capaian_tw1'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['target_tw2'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['realisasi_tw2'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['capaian_tw2'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['target_tw3'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['realisasi_tw3'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['capaian_tw3'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['target_tw4'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['realisasi_tw4'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['capaian_tw4'] ?? '' }}</td>
                    <td>
                        @if($ind['langkah']->count() > 0)
                                @php $y=0; @endphp
                                @foreach($ind['langkah'] as $langkah)
                                @php $y++; @endphp
                                <ol>
                                    <li> {{ $langkah['langkah'] }}
                                </ol>
                                @endforeach
                        @else
                            <p> - </p>
                        @endif</td>
                    <td>Rp. {{ number_format($node['anggaran_perjanjian_kinerja']['murni'], 0, ",", ".") ?? 0 }}</td>
                    <td>{{ @$ind['rencana_aksi']['hambatan'] ?? '' }}</td>
                    <td>{{ @$ind['rencana_aksi']['tindak_lanjut'] ?? '' }}</td>
                </tr>
                @endif
                @php $n++; @endphp
                @endforeach
                @endif
                @php $i++; @endphp
            @endforeach
            @else
            <tr>
                <td colspan="100%" style="text-align:center;">No data found</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
