@extends('layouts.app-v2')

@push('css')
    <!-- Bootstrap CSS (lokal) -->
    <link href="{{ asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- DataTables + Buttons + Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-1.13.10/b-2.4.1/datatables.min.css">

    <!-- Daterangepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
    /* Table responsive */
    .table-responsive {
        overflow-x: auto;
    }

    /* Table layout auto supaya menyesuaikan konten */
    #activity-table {
        width: 100%;
        table-layout: auto;
    }

    /* Semua kolom teks panjang */
    #activity-table th,
    #activity-table td {
        white-space: normal;
        word-wrap: break-word;
    }

    /* Kolom pertama tetap kecil */
    th:first-child,
    td:first-child {
        width: 1% !important;
        white-space: nowrap;
        padding-left: 0.25rem !important;
        padding-right: 0.25rem !important;
    }

    /* Khusus kolom yang butuh lebih luas */
    #activity-table th.judul, #activity-table td.judul { min-width: 300px; }
    #activity-table th.pj, #activity-table td.pj { min-width: 150px; }
    #activity-table th.activity-budget, #activity-table td.activity-budget,
    #activity-table th.workgroup, #activity-table td.workgroup { min-width: 150px; }
    #activity-table th.workteam, #activity-table td.workteam { min-width: 350px; }
    #activity-table th.status, #activity-table td.status { min-width: 120px; }
    #activity-table th.period, #activity-table td.period { min-width: 120px; }
    #activity-table th.completed-tasks, #activity-table td.completed-tasks { min-width: 250px; }
    #activity-table th.issues, #activity-table td.issues { min-width: 250px; }
    #activity-table th.follow-ups, #activity-table td.follow-ups { min-width: 200px; }
    #activity-table th.planned-tasks, #activity-table td.planned-tasks { min-width: 250px; }

    /* Khusus Target & Realisasi */
    #activity-table th.financial-target, #activity-table td.financial-target,
    #activity-table th.financial-realization, #activity-table td.financial-realization {
        min-width: 150px; /* lebar untuk keuangan */
    }

    #activity-table th.physical-target, #activity-table td.physical-target,
    #activity-table th.physical-realization, #activity-table td.physical-realization {
        width: 100px; /* fisik tetap sempit */
    }

    
    .select-ellipsis {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cool-mist {
        background: #a8dadc;
        color: #1d1d1d;    
    }
</style>


<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<main id="main" class="main">
    <section class="section">

        <!-- Session Alert -->
        @if (session('success'))
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                    width="16" height="16" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
                <div>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        <!-- Content Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <!-- Card Body -->
                        <h4 class="card-title">Detail Data Kegiatan</h4>

                        <div class="row">
                            <!-- Button Section -->
                            <div class="col mb-2 d-flex">
                                <div>
                                    <button onclick="history.back()" class="btn btn-warning text-light me-1">Kembali</button>
                                </div>
                                <div>
                                    <a href="{{ route('activity.create') }}" class="btn btn-primary me-1">Tambah</a>
                                </div>
                                <div>
                                    <form action="{{ route('excel') }}" method="post" class="me-1">
                                        @csrf
                                        <div>
                                            <button type="submit" class="btn btn-success"
                                                id="excelButton">Excel</button>
                                            <input type="hidden" name="excelDataShow" id="excelDataShow" value="{{ $activityShow->id }}">
                                        </div>
                                    </form>
                                </div>
                                <div>
                                    <form action="#" method="post" class="me-1">
                                        @csrf
                                        <div>
                                            <button type="submit" class="btn btn-danger" id="PDFButton">PDF</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table id="kegiatanTable" class="table table-striped" style="width:100%">
                                <thead class="">
                                    <tr>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">
                                            #
                                        </th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">
                                            Judul Kegiatan
                                        </th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">
                                            PJ Kegiatan
                                        </th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">
                                            Anggaran Kegiatan (Rp)
                                        </th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">
                                            Kelompok
                                        </th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">
                                            Subkelompok
                                        </th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">
                                            Status
                                        </th>
                                        <th rowspan="1" colspan="4" class="text-center align-middle">Target dan
                                            Realisasi</th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">Kegiatan
                                            yang
                                            sudah dikerjakan</th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">
                                            Permasalahan
                                        </th>
                                        <th rowspan="3" colspan="1" style="width: 10%"
                                            class="text-center align-middle">Tindak Lantjut</th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">Kegiatan
                                            yang
                                            akan dilakukan ()</th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">Tanggal
                                        </th>
                                        @can('update-kegiatan', $activityShow)
                                        <th rowspan="3" colspan="1" class="text-center align-middle">Action
                                        </th>
                                        @endcan
                                    </tr>
                                    <tr>
                                        <th rowspan="1" colspan="2" class="text-center align-middle">Keuangan (Rp)
                                        </th>
                                        <th rowspan="1" colspan="2" class="text-center align-middle">Fisik (%)</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="1" colspan="1" class="text-center align-middle">T</th>
                                        <th rowspan="1" colspan="1" class="text-center align-middle">R</th>
                                        <th rowspan="1" colspan="1" class="text-center align-middle">T</th>
                                        <th rowspan="1" colspan="1" class="text-center align-middle">R</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td>1.</td> 
                                    <td>{{ $activityShow->nama }}</td>
                                    <td>{{ $activityShow->pj->nama }}</td>
                                    <td>{{ formatRupiahAngka($activityShow->anggaran_kegiatan) }}</td>
                                    <td>{{ $activityShow->kelompok->nama }}</td>
                                    <td>{{ $activityShow->subkelompok->nama }}</td>
                                    <td>{{ $activityShow->status->nama }}</td>
                                    <td>{{ formatRupiahAngka($activityShow->target_keuangan) }}</td>
                                    <td>{{ formatRupiahAngka($activityShow->realisasi_keuangan) }}</td>
                                    <td>{{ $activityShow->target_fisik }}</td>
                                    <td>{{ $activityShow->realisasi_fisik }}</td>
                                    <td>
                                        @foreach ($activityShow->dones as $dones )
                                            <p>{{ $loop->iteration}}. {{ $dones }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($activityShow->problems as $problems )
                                            <p>{{ $loop->iteration}}. {{ $problems }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($activityShow->follow_up as $follow_up )
                                            <p>{{ $loop->iteration}}. {{ $follow_up }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($activityShow->todos as $todos )
                                            <p>{{ $loop->iteration}}. {{ $todos }}</p>
                                        @endforeach
                                    </td>
                                    <td>{{ $activityShow->created_at }}</td>
                                    <td>
                                        <div class="d-flex">
                                            @can('update-kegiatan', $activityShow)
                                           <a class="btn btn-warning mx-1" href="{{ route('kegiatan-edit', $activityShow->id) }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Edit Pengaduan">
                                            <i class="bi bi-pencil"></i>
                                            </a>
                                            @endcan
                                            @can('superAdminAndAdmin')
                                            <form action="{{ route('kegiatan-delete', $activityShow->id) }}" method="POST">
                                                @method('delete')
                                                @csrf
                                                <button class="btn btn-danger" onclick="return confirm('Apakah anda ingin menghapus pengaduan?')"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                        data-bs-title="Hapus Pengaduan" >
                                                    <i class="bi bi-trash text-body-secondary"></i>
                                                </button>
                                            </form>
                                        @endcan
                                        </div>  
                                    </td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection


@push('scripts')
    {{-- Handling Excel Data Show Export --}}
    {{-- <script>

        $(document).ready(function(){
            // $("#excelDataShow").val
            console.log($activityShow->id)
        });
    </script> --}}
@endpush