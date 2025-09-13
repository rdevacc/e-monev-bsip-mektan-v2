@extends('layouts.app-v2')

@push('css')
    <!-- Bootstrap CSS (lokal) -->
    <link href="{{ asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- DataTables + Buttons + Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-1.13.10/b-2.4.1/datatables.min.css">

    <!-- Daterangepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .select-ellipsis {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        th:first-child,
        td:first-child {
            width: 1% !important;
            white-space: nowrap;
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
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
        <div class="pagetitle">
            <h1>Data Kegiatan BRMP Mektan</h1>
        </div>

        <!-- Session Alert -->
        @if (session('success'))
            <div class="alert alert-primary d-flex align-items-center justify-content-between" role="alert">
                <div class="d-flex text-center align-middle">
                    {{ session('success') }}
                </div>
                <div class="justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <div class="card cool-mist mb-4">
            <div class="card-body">
                <div class="row mx-1 py-2 d-flex justify-content-between align-items-center">
                    <div class="row mx-1 mb-3">
                        <div class="col-12 col-md-3">
                            <label for="filterPJ" class="form-label">PJ Kegiatan</label>
                            <select id="filterPJ" class="form-select">
                                <option selected value="">Pilih PJ Kegiatan</option>
                                @foreach ($pjList as $pj)
                                    <option value="{{ $pj->id }}">{{ $pj->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="filterWorkGroup" class="form-label">Kelompok Kerja</label>
                            <select id="filterWorkGroup" class="form-select">
                                <option selected value="">Pilih Kelompok Kerja</option>
                                @foreach ($workGroupList as $workGroup)
                                    <option value="{{ $workGroup->id }}">{{ $workGroup->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="filterWorkTeam" class="form-label">Tim Kerja</label>
                            <select id="filterWorkTeam" class="form-select">
                                <option selected value="">Pilih Tim Kerja</option>
                                @foreach ($workTeamList as $workTeam)
                                    <option value="{{ $workTeam->id }}">{{ $workTeam->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mx-1 mb-3">
                        <div class="input-group">
                            <input type="text" id="text_search" class="form-control" placeholder="Cari judul kegiatan...">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                    <div class="col-md-auto d-flex gap-2 mx-3">
                        <a href="{{ route('activity.create') }}" class="btn btn-primary">Tambah</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table id="activity-table" class="table table-striped" style="width:100%">
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
                                            Kelompok Kerja
                                        </th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">
                                            Tim Kerja
                                        </th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">
                                            Status Kegiatan
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
                                        <th rowspan="3" colspan="1" class="text-center align-middle">Kegiatan yang akan dilakukan</th>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">Action</th>
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap Bundle -->
    <script src="{{ asset('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- DataTables + Buttons -->
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.10/b-2.4.1/datatables.min.js"></script>

    <!-- Daterangepicker -->
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/delete-confirmation.js') }}"></script>

    <!-- Initialize DataTables -->
    <script>
        function formatNumberToRupiah(data) {
            if (data === null || data === undefined || data === '') {
                return '-';
            }

            // buang desimal .0
            let numberString = data.toString().split('.')[0];  
            let number = parseInt(numberString, 10);

            if (isNaN(number)) return '-';

            return number.toLocaleString('id-ID'); // 227200000 → 227.200.000
        }

        $(function () {
            var activityTable = $('#activity-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('activity.index') }}",
                    data: function(data) {
                        data.filterPJ = $('#filterPJ').val();
                        data.filterWorkGroup = $('#filterWorkGroup').val();
                        data.filterWorkTeam = $('#filterWorkTeam').val();
                        data.text_search = $('#text_search').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '10px',
                        orderable: false,
                        searchable: false,
                        targets: 0,
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'pj',
                        name: 'pj',
                    },
                    {
                        data: 'activity_budget',
                        name: 'activity_budget',
                        render: function(data, type, row, meta) {
                            return formatNumberToRupiah(data);
                        }
                    },
                    {
                        data: 'work_group',
                        name: 'work_group',
                    },
                    {
                        data: 'work_team',
                        name: 'work_team',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'financial_target',
                        name: 'financial_target',
                        render: function(data, type, row, meta) {
                            return formatNumberToRupiah(data);
                        }
                    },
                    {
                        data: 'financial_realization',
                        name: 'financial_realization',
                        render: function(data, type, row, meta) {
                            return formatNumberToRupiah(data);
                        }
                    },
                    {
                        data: 'physical_target',
                        name: 'physical_target',
                        render: function(data, type, row, meta) {
                            return formatNumberToRupiah(data);
                        }
                    },
                    {
                        data: 'physical_realization',
                        name: 'physical_realization',
                        render: function(data, type, row, meta) {
                            return formatNumberToRupiah(data);
                        }
                    },
                    {
                        data: 'monthly_completed_tasks',
                        name: 'monthly_completed_tasks',
                        render: function(data, type, row, meta) {
                            var result = '';
                            var index = 1;
                            if (data && data.length > 0) {
                                for (var i = 0; i < data.length; i++) {
                                    result += index + ". " + data[i] + "<br><br>";
                                    index++;
                                }
                                return result;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        data: 'monthly_issues',
                        name: 'monthly_issues',
                        render: function(data, type, row, meta) {
                            var result = '';
                            var index = 1;
                            if (data && data.length > 0) {
                                for (var i = 0; i < data.length; i++) {
                                    result += index + ". " + data[i] + "<br><br>";
                                    index++;
                                }
                                return result;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        data: 'monthly_follow_ups',
                        name: 'monthly_follow_ups',
                        render: function(data, type, row, meta) {
                            var result = '';
                            var index = 1;
                            if (data && data.length > 0) {
                                for (var i = 0; i < data.length; i++) {
                                    result += index + ". " + data[i] + "<br><br>";
                                    index++;
                                }
                                return result;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        data: 'monthly_planned_tasks',
                        name: 'monthly_planned_tasks',
                        render: function(data, type, row, meta) {
                            var result = '';
                            var index = 1;
                            if (data && data.length > 0) {
                                for (var i = 0; i < data.length; i++) {
                                    result += index + ". " + data[i] + "<br><br>";
                                    index++;
                                }
                                return result;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            
            $('#filterPJ').change(function () { 
                console.log($('#filterPJ').val())
                activityTable.draw();
            });

            $('#filterWorkGroup').change(function () { 
                console.log($('#filterWorkGroup').val())
                activityTable.draw();
            });

            $('#filterWorkTeam').change(function () { 
                console.log($('#filterWorkTeam').val())
                activityTable.draw();
            });
        });
    </script>
@endpush
