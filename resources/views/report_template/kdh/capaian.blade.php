<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> Renstra</title>
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
    <h2 style="text-align:center;"> DATA KINERJA GUBERNUR</h2>
    <h2 style="text-align:center;"> PERIODE :  {{ $data['visi']->period_starts }} s/d {{ $data['visi']->period_ends }}</h2>
    <p>Generated at: {{ $data['generated_at'] }}</p>

    <table class="report">
        <thead>           
            <!-- header baris 2 -->
            <tr>
                <th style="width: 6%;" class="col-name" rowspan="3"> No</th>
                <th rowspan="3">Sasaran</th>
                <th rowspan="3">Indikator</th>
                <th colspan="3">Capaian Kinerja Tahun n</th>
                <th colspan="4">Peningkatan dari tahun lalu (n-1)</th>
                <th colspan="3">Perbandingan dengan tahun Terakhir RPJMD</th>
                <th colspan="3">Perbandingan dengan Nasional</th>
            </tr>
            <tr>
                <td rowspan="2"> Target</td>
                <td rowspan="2"> Realisasi</td>
                <td rowspan="2"> Capaian</td>

                <td rowspan="2"> Realisasi (n - 1)</td>
                <td rowspan="2"> Capaian (n - 1)</td>

                <td colspan="2"> Perbandingan dengan tahun lalu</td>
                <td rowspan="2"> Target Tahun Terakhir RPJMD</td>
                <td rowspan="2"> Realisasi tahun n</td>
                <td rowspan="2"> Selisih</td>
                <td rowspan="2"> Rata-rata Nasional</td>
                <td rowspan="2"> Realisasi tahun n</td>
                <td rowspan="2"> Peringkat Nasional</td>
            </tr>

             <tr>
                <td> Realisasi</td>
                <td> Capaian</td>
            </tr>
        </thead>
        
        <tbody> 
        @if($data['tujuan']->count() > 0)    
            @foreach($data['tujuan'] as $tujuan)    
                <tr>
                    <td colspan="18">{{ $tujuan['tujuan'] }}</td>
                </tr>

                @if($tujuan['sasaran']->count() > 0)
                    @php $i = 1; @endphp
                    @foreach($tujuan['sasaran'] as $node)
                    @php $inside_rows = ($node['indikator_sasaran']->count() > 0) ? $node['indikator_sasaran']->count() : 1; @endphp
                        <tr>
                            <td rowspan="{{ $inside_rows }}">{{ $i }}</td>
                            <td rowspan="{{ $inside_rows }}">{{ $node['sasaran'] }}</td>
                    
                        @php $i++; @endphp
                            @if($inside_rows > 0)
                                @php $x=0; @endphp
                                @foreach($node['indikator_sasaran'] as $di)
                                
                                @php $x++; 

                                
                                @endphp
                                    
                                        @if($x==1)
                                        <td> {{ $x }} . {{ $di['indikator'] }}</td>                          
                                        <td> {{ !empty($di['target_tahun_sekarang']) ? $di['target_tahun_sekarang'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_tahun_sekarang']) ? $di['realisasi_tahun_sekarang'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_tahun_sekarang']) ? $di['capaian_tahun_sekarang'] : '' }}</td>
                                        
                                        <td> </td>
                                        <td> </td>

                                        <td> {{ !empty($di['realisasi_tahun_lalu']) ? $di['realisasi_tahun_lalu'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_tahun_lalu']) ? $di['capaian_tahun_lalu'] : '' }}</td>

                                        <td> {{ !empty($di['target_tahun_terakhir']) ? $di['target_tahun_terakhir'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_tahun_sekarang']) ? $di['realisasi_tahun_sekarang'] : '' }}</td>
                                        <td> </td>

                                        <td> - </td>
                                        <td> - </td>
                                        <td> - </td>
                                     
                                        @else
                                        <tr>
                                            <td>{{ $x }} {{ $di['indikator'] }}</td>
                                             <td> {{ !empty($di['target_tahun_sekarang']) ? $di['target_tahun_sekarang'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_tahun_sekarang']) ? $di['realisasi_tahun_sekarang'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_tahun_sekarang']) ? $di['capaian_tahun_sekarang'] : '' }}</td>
                                        
                                        <td> </td>
                                        <td> </td>

                                        <td> {{ !empty($di['realisasi_tahun_lalu']) ? $di['realisasi_tahun_lalu'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_tahun_lalu']) ? $di['capaian_tahun_lalu'] : '' }}</td>

                                        <td> {{ !empty($di['target_tahun_terakhir']) ? $di['target_tahun_terakhir'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_tahun_sekarang']) ? $di['realisasi_tahun_sekarang'] : '' }}</td>
                                        <td> </td>

                                        <td> - </td>
                                        <td> - </td>
                                        <td> - </td>

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
                        <td colspan="5" style="text-align:center;">Sasaran data found</td>
                    </tr>
                    @endif
            @endforeach
            @else
            <tr>
                <td colspan="5" style="text-align:center;">Tujuan data found</td>
            </tr>
            @endif
      </tbody>
    </table>
</body>
</html>

