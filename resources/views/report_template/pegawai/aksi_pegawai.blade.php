<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> Laporan Rencana Aksi</title>
<style>
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
    th, td { border:1px solid #bbb; padding:6px; vertical-align: top; word-wrap:break-word; }
    /* Header styling */
    thead th {
        background: #f5f5f5;
        font-weight: 700;
        padding: 4px 6px;
        border: 1px solid #bbb;
        font-size: 8.5px;
    }

    .indent { display:inline-block; }
    /* hindari pemotongan baris pada dompdf (sebagian didukung) */
    tr { page-break-inside: avoid; }
    /* sesuaikan ukuran kolom */
    .col-no { width:40px; }
    .col-name { width:300px; }
    .col-code { width:120px; }
</style>
</head>
<body>
    <h2 style="text-align:center;">Rencana Aksi Pegawai</h2>
    <h2 style="text-align:center;">TAHUN {{ $data['tahun'] }}</h2>
    <p>Generated at: {{ $data['generated_at'] }}</p>
    <table class="report">
        <tr>
            <td> NIP </td>
            <td> :  {{ $data['profil']['nip'] }}</td>
        </tr>
        <tr>
            <td> NAMA </td>
            <td> :  {{ $data['profil']['name'] }}</td>
        </tr>        
        <tr>
            <td> JABATAN </td>
            <td> :  {{ $data['profil']['jabatan_nm'] }}</td>
        </tr>
        <tr>
            <td> UNIT KERJA </td>
            <td> :  {{ $data['profil']['opd_nm'] }}</td>
        </tr>

    </table>
    <p>&nbsp;</p>
     <table class="report">
        <thead>           
            <!-- header baris 2 -->
            <tr>
                <th style="width: 6%;" class="col-name" rowspan="2"> No</th>
                <th rowspan="2">Sasaran</th>
                <th rowspan="2">Indikator</th>
                <th colspan="4">Target Kinerja</th>
                <th rowspan="2">Langkah</th>
                <th rowspan="2">Anggaran</th>
            </tr>
            <tr>
                <td> TW 1 </td>
                <td> TW 2 </td>
                <td> TW 3 </td>
                <td> TW 4 </td>
            </tr>
        </thead>

        <tbody>
            @if($data['sasaran']->count() > 0)
            @php $i = 1; @endphp
            @foreach($data['sasaran'] as $node)
                <tr>
                    <td rowspan="{{ $node['indikator_sasaran']->count() }}">{{ $i }}</td>
                    <td rowspan="{{ $node['indikator_sasaran']->count() }}">{{ $node['sasaran'] }}</td>
               
                @php $i++; @endphp
                    @if($node['indikator_sasaran']->count() > 0)
                         @php $x=0; @endphp
                        @foreach($node['indikator_sasaran'] as $di)
                         @php $x++; @endphp
                              
                                @if($x==1)
                                <td> {{ $x }} . {{ $di['indikator'] }}</td>
                                <td> {{ !empty($di['rencana_aksi']['target_tw1']) ? $di['rencana_aksi']['target_tw1'] : '' }}</td>
                                <td> {{ !empty($di['rencana_aksi']['target_tw2']) ? $di['rencana_aksi']['target_tw2'] : '' }}</td>
                                <td> {{ !empty($di['rencana_aksi']['target_tw3']) ? $di['rencana_aksi']['target_tw3'] : '' }}</td>
                                <td> {{ !empty($di['rencana_aksi']['target_tw4']) ? $di['rencana_aksi']['target_tw4'] : '' }}</td>

                                <td rowspan="{{ $node['indikator_sasaran']->count() }}">
                                    @if($di['langkah']->count() > 0)
                                            @php $y=0; @endphp
                                         @foreach($di['langkah'] as $langkah)
                                            @php $y++; @endphp
                                            <ol>
                                                <li> {{ $langkah['langkah'] }}
                                            </ol>
                                         @endforeach
                                    @else
                                     <p> - </p>
                                    @endif
                                </td>

                                <td rowspan="{{ $node['indikator_sasaran']->count() }}">
                                    {{ !empty($node['anggaran_perjanjian_kinerja']['murni']) ? $node['anggaran_perjanjian_kinerja']['murni'] : '' }}
                                </td>
                                @else
                                <tr>
                                    <td>{{ $x }} {{ $di['indikator'] }}</td>
                                      <td> {{ !empty($di['rencana_aksi']['target_tw1']) ? $di['rencana_aksi']['target_tw1'] : '' }}</td>
                                <td> {{ !empty($di['rencana_aksi']['target_tw2']) ? $di['rencana_aksi']['target_tw2'] : '' }}</td>
                                <td> {{ !empty($di['rencana_aksi']['target_tw3']) ? $di['rencana_aksi']['target_tw3'] : '' }}</td>
                                <td> {{ !empty($di['rencana_aksi']['target_tw4']) ? $di['rencana_aksi']['target_tw4'] : '' }}</td>td>
                                </tr>
                                @endif
                            
                            
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" style="text-align:center;">No data found</td>
                        </tr>
                        @endif   
                         
                 </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5" style="text-align:center;">No data found</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
