<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>SKP</title>
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
        margin-top:10px;
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

    .baris{
    display: flex;
    justify-content: space-between;
    }

    .kiri{
    float: left;
    }

    .kanan{
    float: right;
    }

    .border-0{ border:none !important;}
</style>
</head>
<body>
    <h2 style="text-align:center;">SASARAN KINERJA PEGAWAI</h2>
    <h2 style="text-align:center;">BAGI PEJABAT ADMINISTRASI DAN PEJABAT FUNGSIONAL</h2>
    <p>Generated at: {{ $data['generated_at'] }}</p>

    <div class="baris">
        <span class="kiri"> PEMERINTAH PROVINSI SUMATERA BARAT</span>
        <span class="kanan"> PERIODE :  {{ $data['data_skp']['periode_awal'] }} s/d {{ $data['data_skp']['periode_akhir'] }}</span>
    </div><br>

    <table class="report" width="100%">
        <tr style="background-color:#DEEBF7;">
            <td width="5%"> NO </td>
            <td colspan="2" align="center"> PEGAWAI </td>
            <td> NO </td>
            <td colspan="2" align="center"> ATASAN </td>
        </tr>

        <tr>
            <td> 1 </td>
            <td> NAMA </td>
            <td> :  {{ $data['profil']['name'] }}</td>
            <td> 1 </td>
            <td> NAMA </td>
            <td> :  {{ $data['atasan']['nama_atasan'] }}</td>
        </tr>

        <tr>
            <td> 2 </td>
            <td> NIP </td>
            <td> :  {{ $data['profil']['nip'] }}</td>
            <td> 2 </td>
            <td> NIP </td>
            <td> :  {{ $data['atasan']['nip_atasan'] }}</td>
        </tr>

        <tr>
            <td> 3 </td>
            <td> JABATAN </td>
            <td> :  {{ $data['profil']['jabatan_nm'] }}</td>
            <td> 3 </td>
            <td> JABATAN </td>
            <td> :  {{ $data['atasan']['jabatan_atasan'] }}</td>
        </tr>


        <tr>
            <td> 4 </td>
            <td> UNIT KERJA </td>
            <td> :  {{ $data['profil']['sub_opd_nm'] }}</td>
            <td> 4 </td>
            <td> UNIT KERJA </td>
            <td> :  {{ $data['atasan']['unit_kerja_atasan'] }}</td>
        </tr>

        
       

    </table>
    <p>&nbsp;</p>

    
    <table class="report">
        <thead>
            <!-- header baris 1 (lebih kompleks) -->
            <tr >
                <th colspan="9" style="background-color:#DEEBF7;" align="left"> Hasil Kerja </th>
            </tr>   
            <tr>
                <th class="col-no" rowspan="2" width="5%">No</th>
                <th rowspan="2" width="25%">Sasaran Kerja Pimpinan Yang Diintervensi</th>
                <th rowspan="2" width="30%" class="col-code" >Sasaran Kerja Pegawai</th>
                <th rowspan="2" width="30%"  class="col-code">Indikator Kinerja Individu</th>
                <th colspan="4" width="10%">TARGET</th>
                <th rowspan="2" width="10%">RENCANA AKSI</th>
            </tr>
            <!-- header baris 2 -->
            <tr>
                <th text-align="center">TW 1</th>
                <th text-align="center">TW 2</th>
                <th text-align="center">TW 3</th>
                <th text-align="center">TW 4</th>
            </tr>
        </thead>
            <!---------Sasaran Atasan  -->
           @if($data['data_skp']['sasaran_atasan']->count() > 0)
            @php $i = 1; @endphp
            @foreach($data['data_skp']['sasaran_atasan'] as $node)

            <tr>
                <td> {{ $i }} </td>
                <td colspan="6"><strong> {{ $node['sasaran'] }} </strong> </td>
                <td > &nbsp; </strong> </td>
            </tr>
                 
                <!---------Sasaran Pegawai  -->
                 @if($node['sasaran_pegawai']->count() > 0)
                  @php $i = 1; @endphp
                  @foreach($node['sasaran_pegawai'] as $sp)
                    @php 
                        $i++; 
                        $inside_rows = ($sp['indikator']->count() > 0) ? $sp['indikator']->count() : 1; 
                    @endphp
                        <tr>
                            <td rowspan="{{ $inside_rows }}" colspan="2"> &nbsp;</td>
                            <td rowspan="{{ $inside_rows }}"> {{ $sp['sasaran'] }}</td>
                               @php $x=0; @endphp
                                @foreach($sp['indikator'] as $di)
                                    @php $x++; @endphp
                                    @if($x==1)
                                    <td> {{ $di['indikator'] }}</td>                          
                                    <td text-align="center"> {{ !empty($di['target_tw1']) ? $di['target_tw1'] : '' }}</td>
                                    <td text-align="center"> {{ !empty($di['target_tw2']) ? $di['target_tw2'] : '' }}</td>
                                    <td text-align="center"> {{ !empty($di['target_tw3']) ? $di['target_tw3'] : '' }}</td>
                                    <td text-align="center"> {{ !empty($di['target_tw4']) ? $di['target_tw4'] : '' }}</td>
                                    <td text-align="left">
                                        @foreach($di['langkah'] as $aksi)
                                            <ol>
                                                <li> {{ $aksi['langkah'] }}</li>
                                            </ol>
                                        @endforeach
                                    </td>
                                    @else
                                    <tr>
                                        <td>{{ $di['indikator'] }}</td>
                                        <td text-align="center"> {{ !empty($di['target_tw1']) ? $di['target_tw1'] : '' }}</td>
                                        <td text-align="center"> {{ !empty($di['target_tw2']) ? $di['target_tw2'] : '' }}</td>
                                        <td text-align="center"> {{ !empty($di['target_tw3']) ? $di['target_tw3'] : '' }}</td>
                                        <td text-align="center"> {{ !empty($di['target_tw4']) ? $di['target_tw4'] : '' }}</td>
                                        <td text-align="left">
                                        @foreach($di['langkah'] as $aksi)
                                                <ol>
                                                    <li> {{ $aksi['langkah'] }}</li>
                                                </ol>
                                            @endforeach
                                        </td>
                                        
                                    </tr>
                                    @endif
                                @endforeach 
                        </tr>
                   @endforeach                 
                 @else
                 <tr>
                    <td colspan="100%" style="text-align:center;">Sasaran Not found< /td>
                 </tr>
                @endif
                <!---------Sasaran Pegawai  -->

            <!---------Sasaran Atasan  -->
            @php $i++; @endphp
            @endforeach
            @else
            <tr>
                <td colspan="100%" style="text-align:center;">No data found< /td>
            </tr>
            @endif
       
    </table> 


     <table width="100%" class="border-0">
        <tr class="border-0">
            <td class="border-0" colspan="2" align="right"> Padang, <?php echo date('Y-m-d');?> </td>
        </tr>

        <tr class="border-0">
            <td class="border-0"> Pegawai</td>
            <td class="border-0"> Atasan </td>
        </tr>

        <tr class="border-0">
            <td class="border-0">
                <p style="margin-top:3rem;">  {{ $data['profil']['name']}} </p>
                <p> {{ $data['profil']['nip']}}</p> 
            </td>
            <td class="border-0" text-align="right"> 
                 <p style="margin-top:3rem;"> {{ $data['atasan']['nama_atasan'] }} </p>
                <p> {{ $data['atasan']['nip_atasan'] }} </p>
            </td>
        </tr>


    </table>
</body>
</html>

