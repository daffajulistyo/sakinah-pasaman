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
                <th colspan="15">Target Kinerja</th>
            </tr>
            <tr>
                <td colspan="3"> Tahun 1</td>
                <td colspan="3"> Tahun 2</td>
                <td colspan="3">  Tahun 3</td>
                <td colspan="3"> Tahun 4</td>
                <td colspan="3"> Tahun 5</td>
            </tr>

             <tr>
                <td> Target</td>
                <td> Realisasi</td>
                <td> Capaian</td>

                <td> Target</td>
                <td> Realisasi</td>
                <td> Capaian</td>

                <td> Target</td>
                <td> Realisasi</td>
                <td> Capaian</td>

                <td> Target</td>
                <td> Realisasi</td>
                <td> Capaian</td>

                <td> Target</td>
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
                                
                                @php $x++; @endphp
                                    
                                        @if($x==1)
                                        <td> {{ $x }} . {{ $di['indikator'] }}</td>                          
                                        <td> {{ !empty($di['target_1']) ? $di['target_1'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_1']) ? $di['realisasi_1'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_1']) ? $di['capaian_1'] : '' }}</td>
                                        
                                        <td> {{ !empty($di['target_2']) ? $di['target_2'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_2']) ? $di['realisasi_2'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_2']) ? $di['capaian_2'] : '' }}</td>

                                        <td> {{ !empty($di['target_3']) ? $di['target_2'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_3']) ? $di['realisasi_3'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_3']) ? $di['capaian_3'] : '' }}</td>

                                        <td> {{ !empty($di['target_4']) ? $di['target_4'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_4']) ? $di['realisasi_4'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_4']) ? $di['capaian_4'] : '' }}</td>

                                        <td> {{ !empty($di['target_5']) ? $di['target_5'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_5']) ? $di['realisasi_5'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_5']) ? $di['capaian_5'] : '' }}</td>
                                     
                                        @else
                                        <tr>
                                            <td>{{ $x }} {{ $di['indikator'] }}</td>
                                             <td> {{ !empty($di['target_1']) ? $di['target_1'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_1']) ? $di['realisasi_1'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_1']) ? $di['capaian_1'] : '' }}</td>
                                        
                                        <td> {{ !empty($di['target_2']) ? $di['target_2'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_2']) ? $di['realisasi_2'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_2']) ? $di['capaian_2'] : '' }}</td>

                                        <td> {{ !empty($di['target_3']) ? $di['target_3'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_3']) ? $di['realisasi_3'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_3']) ? $di['capaian_3'] : '' }}</td>

                                        <td> {{ !empty($di['target_4']) ? $di['target_4'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_4']) ? $di['realisasi_4'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_4']) ? $di['capaian_4'] : '' }}</td>

                                        <td> {{ !empty($di['target_5']) ? $di['target_5'] : '' }}</td>
                                        <td> {{ !empty($di['realisasi_5']) ? $di['realisasi_5'] : '' }}</td>
                                        <td> {{ !empty($di['capaian_5']) ? $di['capaian_5'] : '' }}</td>
                                        </td>

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
