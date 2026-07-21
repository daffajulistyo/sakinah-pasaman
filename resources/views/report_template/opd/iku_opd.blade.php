<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Tree Report</title>
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
    <h2 style="text-align:center;">LAPORAN INDIKATOR KINERJA UTAMA OPD</h2>
    <h2 style="text-align:center;">{{ $data['opd']['nama_opd'] }}</h2>
    <h2 style="text-align:center;"> PERIODE VISI :  {{ $data['visi']->period_starts }} s/d {{ $data['visi']->period_ends }}</h2>
    <p>Generated at: {{ $data['generated_at'] }}</p>

    <table>
        <thead>           
            <tr>
                <th style="width: 6%;" class="col-name">No</th>
                <th width="40%">Indikator</th>
                <th>Baseline</th>
                <th>Rilis</th>
                <th>Sumber Data</th>
            </tr>
        </thead>

        <tbody>
            @if($data['indikator']->count() > 0)
            @php $i = 1; @endphp
            @foreach($data['indikator'] as $node)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $node['indikator'] }}</td>
                    <td>{{ $node['baseline'] }}</td>
                    <td>{{ $node['rilis'] }}</td>
                    <td>{{ $node['sumber_data'] }}</td>
                </tr>
                @php $i++; @endphp
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
