<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> Laporan PK</title>
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
    <h2 style="text-align:center;">PERJANJIAN KINERJA PEGAWAI</h2>
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
           
            <tr>
                <th style="width: 6%;" class="col-name">No</th>
                <th>Sasaran</th>
                <th>Indikator</th>
                <th>Target</th>
                <th>Anggaran</th>
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
                                <td> {{ !empty($di['perjanjian_kinerja']['target']) ? $di['perjanjian_kinerja']['target'] : '' }}</td>
                                <td rowspan="{{ $node['indikator_sasaran']->count() }}">{{ $node['anggaran'] }}</td>
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
