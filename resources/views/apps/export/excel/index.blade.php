@extends('layouts.app-v2')

@section('title')
    Export | E-Monev BBRM Mektan
@endsection

@push('css')
    <!-- Bootstrap CSS (lokal) -->
    <link href="{{ asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- DataTables + Buttons + Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-1.13.10/b-2.4.1/datatables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css"/>

    <!-- Daterangepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .table-responsive { overflow-x: auto; }
        #activity-table { width: 100%; table-layout: auto; }
        #activity-table th, #activity-table td { white-space: normal; word-wrap: break-word; }
        th:first-child, td:first-child { width: 1% !important; white-space: nowrap; padding-left: 0.25rem !important; padding-right: 0.25rem !important; }

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

        #activity-table th.financial-target, #activity-table td.financial-target,
        #activity-table th.financial-realization, #activity-table td.financial-realization { min-width: 200px; text-align: center !important; vertical-align: middle !important; }

        #activity-table th.physical-target, #activity-table td.physical-target,
        #activity-table th.physical-realization, #activity-table td.physical-realization { min-width: 100px; text-align: center !important; vertical-align: middle !important; }

        .select-ellipsis { max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .cool-mist { background: #a8dadc; color: #1d1d1d; }

        /* Tambahan untuk centang di Select2 */
        .select2-results__option input[type="checkbox"] {
            margin-right: 6px;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="pagetitle">
            <h1>Export Kegiatan BBRM Mektan</h1>
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
                            <label for="filterYear" class="form-label">Tahun</label>
                            <select id="filterYear" class="form-select" multiple="multiple">
                                @foreach ($years as $year)
                                    <option value="{{ $year['id'] }}">{{ $year['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="filterMonth" class="form-label">Bulan</label>
                            <select id="filterMonth" class="form-select" multiple="multiple">
                                @foreach ($months as $month)
                                    <option value="{{ $month['id'] }}">{{ $month['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="filterPJ" class="form-label">PJ Kegiatan</label>
                            <select id="filterPJ" class="form-select">
                                <option selected value="">Pilih PJ Kegiatan</option>
                                @foreach ($pjList as $pj)
                                    <option value="{{ $pj->id }}">{{ $pj->name }}</option>
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

                    <div class="row mx-1 mb-3">
                        <div class="col-md-3 mb-2 mb-md-0">
                            <button id="exportExcel" class="btn btn-success w-100" disabled>
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                            </button>
                        </div>
                        {{-- <div class="col-md-3 mb-2 mb-md-0">
                            <button id="exportPdf" class="btn btn-danger w-100" disabled>
                                <i class="bi bi-file-earmark-pdf"></i> Export PDF
                            </button>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Placeholder -->
        <div id="placeholder-message" class="alert alert-info text-center">
            Pilih filter terlebih dahulu untuk menampilkan data kegiatan.
        </div>

        <!-- Content Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <!-- Table hidden by default -->
                        <div id="table-container" class="table-responsive" style="display:none;">
                            <table id="activity-table" class="table table-striped" style="width:100%">
                                <thead class="">
                                    <tr>
                                        <th rowspan="3" colspan="1" class="text-center align-middle">
                                            #
                                        </th>
                                        <th rowspan="3" colspan="1" class="judul text-center align-middle">
                                            Judul Kegiatan
                                        </th>
                                        <th rowspan="3" colspan="1" class="pj text-center align-middle">
                                            PJ Kegiatan
                                        </th>
                                        <th rowspan="3" colspan="1" class="activity-budget text-center align-middle">
                                            Anggaran Kegiatan (Rp)
                                        </th>
                                        {{-- <th rowspan="3" colspan="1" class="workgroup text-center align-middle">
                                            Kelompok Kerja
                                        </th>
                                        <th rowspan="3" colspan="1" class="workteam text-center align-middle">
                                            Tim Kerja
                                        </th> --}}
                                        <th rowspan="3" colspan="1" class="status text-center align-middle">
                                            Status Kegiatan
                                        </th>
                                        <th rowspan="3" colspan="1" class="period text-center align-middle">
                                            Bulan
                                        </th>
                                        <th rowspan="1" colspan="4" class="text-center align-middle" style="font-weight:600; text-align:center; vertical-align:middle; white-space:nowrap;">Target dan
                                            Realisasi</th>
                                        <th rowspan="3" colspan="1" class="completed-tasks text-center align-middle">Kegiatan
                                            yang
                                            sudah dikerjakan</th>
                                        <th rowspan="3" colspan="1" class="issues text-center align-middle">
                                            Permasalahan
                                        </th>
                                        <th rowspan="3" colspan="1" style="width: 10%"
                                            class="follow-ups text-center align-middle">Tindak Lantjut</th>
                                        <th rowspan="3" colspan="1" class="planned-tasks text-center align-middle">Kegiatan yang akan dilakukan</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="1" colspan="2" class="text-center align-middle" style="font-weight:500; text-align:center; vertical-align:middle; white-space:nowrap;">Keuangan (Rp)
                                        </th>
                                        <th rowspan="1" colspan="2" class="text-center align-middle" style="font-weight:500; text-align:center; vertical-align:middle; white-space:nowrap;">Fisik (%)</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="1" colspan="1" class="financial-target text-center align-middle" style="font-weight:500; text-align:center; vertical-align:middle; white-space:nowrap;min-width: 200px;">T</th>
                                        <th rowspan="1" colspan="1" class="financial-realization text-center align-middle" style="font-weight:500; text-align:center; vertical-align:middle; white-space:nowrap; min-width: 200px;">R</th>
                                        <th rowspan="1" colspan="1" class="physical-target text-center align-middle" style="font-weight:500; text-align:center; vertical-align:middle; white-space:nowrap; min-width: 100px;">T</th>
                                        <th rowspan="1" colspan="1" class="physical-realization text-center align-middle" style="font-weight:500; text-align:center; vertical-align:middle; white-space:nowrap; min-width: 100px;">R</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Daterangepicker -->
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/delete-confirmation.js') }}"></script>

    <script>
       $(function () {
            var activityTable = null;

            // Fungsi untuk enable/disable tombol export
            function toggleExportButtons(enable) {
                $('#exportExcel').prop('disabled', !enable);
                $('#exportPdf').prop('disabled', !enable);
            }

            window.buildExportParams = function() {
                return {
                    filterPJ: $('#filterPJ').val(),
                    filterWorkGroup: $('#filterWorkGroup').val(),
                    filterWorkTeam: $('#filterWorkTeam').val(),
                    filterMonth: $('#filterMonth').val(),
                    filterYear: $('#filterYear').val(),
                    text_search: $('#text_search').val(),
                    filterPeriod: $('#filterPeriod').val(),
                };
            };

            function hasFilter() {
                return (
                    $('#filterPJ').val() ||
                    $('#filterWorkGroup').val() ||
                    $('#filterWorkTeam').val() ||
                    ($('#filterYear').val() && $('#filterYear').val().length > 0) ||
                    ($('#filterMonth').val() && $('#filterMonth').val().length > 0)
                );
            }

            function initTable() {
                if ($.fn.DataTable.isDataTable('#activity-table')) {
                    activityTable.ajax.reload();
                    return;
                }

                $('#placeholder-message').hide();
                $('#table-container').show();

                activityTable = $('#activity-table').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    scrollX: true,
                    scrollCollapse: true,
                    paging: true,
                    pageLength: 25,
                    lengthMenu: [[25, 50, 100], [25, 50, 100]],
                    fixedColumns: { leftColumns: 4 },
                    destroy: true,
                    ajax: {
                        url: "{{ route('export.index') }}",
                        data: function (data) {
                            data.filterPJ = $('#filterPJ').val();
                            data.filterWorkGroup = $('#filterWorkGroup').val();
                            data.filterWorkTeam = $('#filterWorkTeam').val();
                            data.text_search = $('#text_search').val();
                            data.filterMonth = $('#filterMonth').val();
                            data.filterYear = $('#filterYear').val();
                        },
                    },
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            width: '10px',
                            orderable: false,
                            searchable: false,
                            targets: 0,
                        },
                        { data: 'name', name: 'name' },
                        { data: 'pj', name: 'pj' },
                        { data: 'activity_budget', name: 'activity_budget' },
                        // { data: 'work_group', name: 'work_group' },
                        // { data: 'work_team', name: 'work_team' },
                        { data: 'status', name: 'status' },
                        { data: 'monthly_period', name: 'monthly_period' },
                        { data: 'financial_target', name: 'financial_target' },
                        { data: 'financial_realization', name: 'financial_realization' },
                        { data: 'physical_target', name: 'physical_target' },
                        { data: 'physical_realization', name: 'physical_realization' },
                        { 
                            data: 'monthly_completed_tasks',
                            name: 'monthly_completed_tasks',
                            render: function(data) {
                            if (Array.isArray(data) && data.length > 0) {
                                return data.map((item, idx) => (idx + 1) + ". " + item).join("<br>");
                            }
                            return '-';
                        } 
                        },
                        { 
                            data: 'monthly_issues',
                            name: 'monthly_issues',
                            render: function(data) {
                                if (Array.isArray(data) && data.length > 0) {
                                    return data.map((item, idx) => (idx + 1) + ". " + item).join("<br>");
                                }
                                return '-';
                            }
                        },
                        { 
                            data: 'monthly_follow_ups',
                            name: 'monthly_follow_ups',
                            render: function(data) {
                                if (Array.isArray(data) && data.length > 0) {
                                    return data.map((item, idx) => (idx + 1) + ". " + item).join("<br>");
                                }
                                return '-';
                            }
                        },
                        {
                            data: 'monthly_planned_tasks',
                            name: 'monthly_planned_tasks',
                            render: function(data) {
                                if (Array.isArray(data) && data.length > 0) {
                                    return data.map((item, idx) => (idx + 1) + ". " + item).join("<br>");
                                }
                                return '-';
                            }
                        },
                    ],
                    ordering: false,
                });

                // Listener untuk enable/disable tombol export
                activityTable.on('xhr.dt', function(e, settings, json) {
                    toggleExportButtons(json.data && json.data.length > 0);
                });
            }

            function toggleTable() {
                if (hasFilter()) {
                    initTable();
                } else {
                    if ($.fn.DataTable.isDataTable('#activity-table')) {
                        activityTable.destroy();
                        $('#activity-table tbody').empty();
                        activityTable = null;
                    }
                    $('#table-container').hide();
                    $('#placeholder-message').show();
                    toggleExportButtons(false);
                }
            }

            $('#text_search').on('keyup', toggleTable);
            $('#filterPJ, #filterWorkGroup, #filterWorkTeam, #filterMonth, #filterYear').on('change', toggleTable);

            // ==== Select2 Checkbox Init ====
            function initSelect2WithCheckbox(selector, placeholder) {
                $(selector).select2({
                    placeholder: placeholder,
                    closeOnSelect: false,
                    allowClear: true,
                    width: '100%',
                    templateResult: function (data) {
                        if (!data.id) return data.text;
                        return $('<span><input type="checkbox"/> ' + data.text + '</span>');
                    },
                    templateSelection: function (data) {
                        return data.text;
                    }
                });

                // Sync checkbox state
                $(selector).on('select2:select', function (e) {
                    var $el = $(e.params.data.element);
                    $el.prop('selected', true);
                });
                $(selector).on('select2:unselect', function (e) {
                    var $el = $(e.params.data.element);
                    $el.prop('selected', false);
                });
            }

            initSelect2WithCheckbox('#filterMonth', "Pilih Bulan");
            initSelect2WithCheckbox('#filterYear', "Pilih Tahun");

            // Tombol Export Excel
            $('#exportExcel').on('click', function (e) {
                e.preventDefault();

                // Tampilkan loading SweetAlert
                Swal.fire({
                    title: 'Sedang meng-export...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Bangun parameter URL dan ubah object menjadi query string
                const params = $.param(buildExportParams());

                // Buat <iframe> tersembunyi untuk menangani download (agar tidak mengganggu halaman)
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = "{{ route('export.excel') }}?" + params;

                // Tambahkan iframe ke body
                document.body.appendChild(iframe);

                // Estimasi waktu proses download selesai (bisa disesuaikan)
                setTimeout(function () {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Export Selesai',
                        text: 'File berhasil diunduh.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }, 3000);
            });
        });
    </script>
@endpush
