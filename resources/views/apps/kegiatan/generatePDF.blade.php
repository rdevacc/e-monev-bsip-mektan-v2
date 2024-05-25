<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Generate PDF </title>

    {{-- Style --}}
    <style>
        #table1 table {padding-bottom: 1rem}
        #table1 table, #table1 tr, #table1 th, #table1 td{border: none;}
        #table2 th {text-align:center; vertical-align:middle;background: grey}
        #table2 table, #table2 th, #table2 td {border: 1px solid black; border-collapse: collapse}
        #table2 tr:nth-child(even) {background: #fff}
        #table2 tr:nth-child(odd) {background: lightsteelblue}
        #table2 td{vertical-align:text-top}
        #table2 p{vertical-align:text-top; padding-top: 0; margin-top: 0;}
    </style>
</head>
<body>
<div class="py-4 md:pl-[17rem] md:pr-4">    
    <div style="padding-bottom:1rem;">
        <span style="font-size: large;"><strong>PROGRES PELAKSANAAN KEGIATAN BSIP MEKTAN TAHUN {{$currentYear}}</strong></span>
    </div>

    <div id="table1">
        <table>
            <tr>
                <td>Sampai dengan akhir bulan</td>
                <th align="left" style="text-transform: uppercase">( {{ $currentMonth }} )</th>
            </tr>
            <tr>
                <td>Kelompok</td>
                {{-- <td>:</td> --}}
                <th align="left" style="text-transform: uppercase">
                    @foreach ($array_kelompoks as $kelompok)
                        ( {{ $kelompok }} ) 
                    @endforeach
                </th>
            </tr>
            <tr>
                <td>Subkelompok</td>
                <th align="left" style="text-transform: uppercase">
                    @foreach ($array_subkelompoks as $subkelompok)
                        ( {{ $subkelompok }} ) 
                    @endforeach
                </th>
            </tr>
            <tr>
                <td>Penanggung Jawab</td>
                {{-- <td>:</td> --}}
                <th align="left" style="text-transform: uppercase">
                    @foreach ($array_pjs as $pj)
                        ( {{ $pj }} ) 
                    @endforeach
                </th>
            </tr>
        </table>
    </div>

   <div id="table2">
    <table id="table2" class="table table-auto border border-red-100" style="border: 1px, solid, black,border-collapse: collapse;">
        <thead class="bg-slate-300">
            <tr>
                <th rowspan="3" colspan="1">No</th>
                <th rowspan="3" colspan="1">Judul Kegiatan</th>
                <th rowspan="3" colspan="1">Anggaran Kegiatan</th>
                <th rowspan="3" colspan="1">Subkelompok</th>
                <th rowspan="3" colspan="1">Kelompok</th>
                <th rowspan="1" colspan="4">Target dan Realisasi</th>
                <th rowspan="3" colspan="1">Kegiatan yang sudah dikerjakan</th>
                <th rowspan="3" colspan="1">Permasalahan</th>
                <th rowspan="3" colspan="1">Tindak Lantjut</th>
                <th rowspan="3" colspan="1">Kegiatan yang akan dilakukan ({{ $next_month }})</th>
                <th rowspan="3" colspan="1">Tanggal</th>
            </tr>
            <tr>
                <th rowspan="1" colspan="2">Keuangan (Rp)</th>
                <th rowspan="1" colspan="2">Fisik (%)</th>
            </tr>
            <tr>
                <th rowspan="1" colspan="1">T</th>
                <th rowspan="1" colspan="1">R</th>
                <th rowspan="1" colspan="1">T</th>
                <th rowspan="1" colspan="1">R</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $kegiatan)
                <tr class="space-y-4 even:bg-sky-100 odd:bg-slate-100" style="even:red;odd:blue;">
                    <td class="align-text-top">
                        <span class="font-semibold align-text-top">{{ $loop->iteration }}.</span>
                    </td>
                    <td class="align-text-top">{{ $kegiatan->nama }}</td>
                    <td class="align-text-top">{{ formatRupiah($kegiatan->anggaran_kegiatan) }}</td>
                    <td class="align-text-top text-center">{{ $kegiatan->subkelompok->nama }}</td>
                    <td class="align-text-top text-center">{{ $kegiatan->kelompok->nama }}</td>
                    <td class="align-text-top">{{ formatRupiahAngka($kegiatan->target_keuangan) }}</td>
                    <td class="align-text-top">{{ formatRupiahAngka($kegiatan->realisasi_keuangan) }}</td>
                    <td class="align-text-top">{{ $kegiatan->target_fisik }}</td>
                    <td class="align-text-top">{{ $kegiatan->realisasi_fisik }}</td>
                    <td class="align-text-top">
                        @foreach ($kegiatan->dones as $value)
                        <p class="align-text-top">
                            {{ $loop->iteration }}. {{$value}}        
                        </p>
                        @endforeach
                    </td>
                    <td class="align-text-top">
                        @foreach ($kegiatan->problems as $value)
                        <p class="align-text-top">
                            {{ $loop->iteration }}. {{$value}}        
                        </p>
                        @endforeach
                    </td>
                    <td class="align-text-top">
                        @foreach ($kegiatan->follow_up as $value)
                        <p class="align-text-top">
                            {{ $loop->iteration }}. {{$value}}        
                        </p>
                        @endforeach
                    </td>
                    <td class="align-text-top">
                        @foreach ($kegiatan->todos as $value)
                        <p class="align-text-top">
                            {{ $loop->iteration }}. {{$value}}        
                        </p>
                        @endforeach
                    </td>
                    <td class="align-text-top">
                        {{ $kegiatan->created_at->format('d F Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
   </div>
</div>
</body>
</html>
