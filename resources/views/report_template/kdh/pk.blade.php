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
    <h2 style="text-align:center;"> PERJANJIAN KINERJA BUPATI</h2>
    <h2 style="text-align:center;"> PERIODE :  {{ $data['periode'] }} TAHUN {{ $data['tahun'] }}</h2>
    <p>Generated at: {{ $data['generated_at'] }}</p>

    <table class="report">
        <thead>           
            <!-- header baris 2 -->
            <tr>
                <th style="width: 6%;" class="col-name"> No</th>
                <th >Sasaran</th>
                <th >Indikator</th>
                <th >Target Kinerja</th>
                <th >Anggaran</th>
            </tr>
        </thead>
        
        <tbody>         
        @if($data['sasaran']->count() > 0)
            @php $i = 1; @endphp
            @foreach($data['sasaran'] as $node)
                <tr>
                    <td rowspan="{{ $node['indikator']->count() }}">{{ $i }}</td>
                    <td rowspan="{{ $node['indikator']->count() }}">{{ $node['sasaran'] }}</td>
               
                @php $i++; @endphp
                    @if($node['indikator']->count() > 0)
                         @php $x=0; @endphp
                        @foreach($node['indikator'] as $di)
                         @php $x++; @endphp
                              
                                @if($x==1)
                                <td> {{ $x }} . {{ $di['indikator'] }}</td>                          
                                <td> {{ !empty($di['renja']->target) ? $di['renja']->target : '' }}</td>
                                <td rowspan="{{ $node['indikator']->count() }}">
                                    {{ !empty($di['anggaran']) ? $di['anggaran'] : '' }}
                                </td>
                                @else
                                <tr>
                                    <td>{{ $x }} {{ $di['indikator'] }}</td>
                                    <td> {{ !empty($di['renja']->target) ? $di['renja']->target : '' }}</td>
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
      </tbody>
    </table>
</body>
</html>

