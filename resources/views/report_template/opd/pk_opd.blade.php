<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> Laporan PK</title>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size:12px; }
    table { width:100%; border-collapse: collapse; table-layout: fixed; }
    th, td { border:1px solid #333; padding:6px; vertical-align: top; word-wrap:break-word; }
    thead th { background: #eee; font-weight:700; text-align:center; }
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
    <h2 style="text-align:center;">PERJANJIAN KINERJA </h2>
    <h2 style="text-align:center;">{{ $data['opd']['nama_opd'] }}</h2>
    <h2 style="text-align:center;">TAHUN {{ $data['tahun'] }}</h2>
    <p>Generated at: {{ $data['generated_at'] }}</p>

    <table>
        <thead>
           
            <tr>
                <th style="width: 5%;" class="col-name">No</th>
                <th width="35%">Sasaran</th>
                <th width="35%"> Indikator</th>
                <th width="10%">Target</th>
                <th width="15%">Anggaran (Rp)</th>
            </tr>
        </thead>

        <tbody>
            @if($data['sasaran']->count() > 0)
            @php $i = 1; @endphp
            @foreach($data['sasaran'] as $node)
                @php $inside_rows = ($node['indikator_sasaran']->count() > 0) ? $node['indikator_sasaran']->count() : 1;  @endphp
                <tr>
                    <td rowspan="{{ $inside_rows }}">{{ $i }}</td>
                    <td rowspan="{{ $inside_rows }}">{{ $node['sasaran'] }}</td>
               
                   @php $i++; @endphp
                    @if($node['indikator_sasaran']->count() > 0)
                         @php $x=0; @endphp
                        @foreach($node['indikator_sasaran'] as $di)
                         @php $x++; @endphp
                              
                                @if($x==1)
                                <td> {{ $x }} . {{ $di['indikator'] }}</td>
                                <td> {{ !empty($di['perjanjian_kinerja']['target']) ? $di['perjanjian_kinerja']['target'] : '' }}</td>
                                <td rowspan="{{ $inside_rows }}">{{ number_format($node['anggaran'], 0, ",", ".") }}</td>
                                @else
                                <tr>
                                    <td>{{ $x }} {{ $di['indikator'] }}</td>
                                     <td> {{ !empty($di['perjanjian_kinerja']['target']) ? $di['perjanjian_kinerja']['target'] : '' }}</td>
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

