@extends('layouts.app-v2')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.0.0/r-3.0.0/datatables.min.css" rel="stylesheet">

    {{-- DataTable Button CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.bootstrap5.css">

    {{-- Daterange CSS --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                            <h4 class="card-title">Data Seluruh Kegiatan 2024</h4>

                            <div class="row">
                                <!-- Button Section -->
                                <div class="col mb-2 d-flex">
                                    <div>
                                        <a href="{{ route('kegiatan-create') }}" class="btn btn-primary me-1">Tambah</a>
                                    </div>
                                    <div>
                                        <form action="{{ route('excel') }}" method="post" class="me-1">
                                            @csrf
                                            <div>
                                                <button type="submit" class="btn btn-success"
                                                    id="excelButton">Excel</button>
                                                <input type="hidden" name="excelDataStart" id="excelDataStart">
                                                <input type="hidden" name="excelDataEnd" id="excelDataEnd">
                                                <input type="hidden" name="excelDataSearch" id="excelDataSearch">
                                            </div>
                                        </form>
                                    </div>
                                    <div>
                                        <form action="#" method="post">
                                            @csrf
                                            <div>
                                                <button type="submit" class="btn btn-danger" id="excelButton">PDF</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Date Range Filter -->
                                    <div class="col-xs-auto ms-2">
                                        <div class="float-end" id="daterange"
                                            style="background: #fff;cursor:pointer;padding: 5px 10px;border:1px solid #ccc;width100%;text-align:center">
                                            <i class="bi bi-calendar"></i>&nbsp;
                                            <span></span>
                                            <i class="bi bi-caret-down-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-2 d-flex">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="searchField"
                                            placeholder="Cari Kegiatan">
                                        <button class="btn btn-outline-secondary" type="button" id="searchFieldBtn">
                                            <i class="bi bi-search"></i>
                                            Cari
                                        </button>
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
                                                Anggaran Kegiatan
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
                                                Realisasi (%)</th>
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
                                            <th rowspan="3" colspan="1" class="text-center align-middle">Action
                                            </th>

                                        </tr>
                                        <tr>
                                            <th rowspan="1" colspan="2" class="text-center align-middle">Keuangan
                                            </th>
                                            <th rowspan="1" colspan="2" class="text-center align-middle">Fisik</th>
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
    <script src="https://cdn.datatables.net/v/bs5/dt-2.0.0/r-3.0.0/datatables.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    {{-- JS DataTable Button --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.bootstrap5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.colVis.min.js"></script>


    {{-- Moment Js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js"
        integrity="sha512-4F1cxYdMiAW98oomSLaygEwmCnIP38pb4Kx70yQYqRwLVCs3DbRumfBq82T08g/4LJ/smbFGFpmeFlQgoDccgg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    {{-- Datepicker JS --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        // Handling Date Range Filter
        var start_date = moment().startOf('year');
        var end_date = moment().add(1, 'day');

        var start_date_convert = start_date.format('YYYY-MM-DD');
        var end_date_convert = end_date.format('YYYY-MM-DD');


        // Handling Search and Excel export filter
        $(document).ready(function() {
            $('#excelDataSearch').val(null);
            $('#start_date').val(start_date);
            $('#end_date').val(end_date);
            // Handle Search
            $('#searchField').on('keyup', function(searchField) {
                var dtSearch = $('#searchField').val();
                $('#excelDataSearch').val(dtSearch);
                kegiatanTable.draw();
            });
            $('#searchFieldBtn').on('click', function(searchField) {
                var dtSearch = $('#searchField').val();
                $('#excelDataSearch').val(dtSearch);
                kegiatanTable.draw();
            });
        });

        $('#daterange span').html(start_date.format('D MMM YY') + ' - ' + end_date.format('D MMM YY'));
        $('#excelDataStart').val(start_date_convert);
        $('#excelDataEnd').val(end_date_convert);

        console.log(start_date._d.toString());
        console.log(end_date._d.toString());


        // Daterange Filter
        $('#daterange').daterangepicker({
            startDate: start_date,
            endDate: end_date,
            autoUpdateInput: false,
            showDropdowns: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year'), moment().subtract(1, 'year').endOf('year')]
            }
        }, function(start_date, end_date) {
            $('#daterange span').html(start_date.format('D MMM YY') + ' - ' + end_date.format(
                'D MMM YY'));
            start_date_convert = start_date.format('YYYY-MM-DD');
            end_date_convert = end_date.format('YYYY-MM-DD');
            $('#excelDataStart').val(start_date_convert);
            $('#excelDataEnd').val(end_date_convert);
            kegiatanTable.draw();
        });

        var kegiatanTable = $('#kegiatanTable').DataTable({
            serverSide: true,
            searching: false,
            processing: true,
            ajax: {
                url: "{{ route('kegiatan-index') }}",
                data: function(data) {
                    data.from_date = $('#daterange').data('daterangepicker').startDate.format(
                        'YYYY-MM-DD');
                    data.end_date = $('#daterange').data('daterangepicker').endDate.format(
                        'YYYY-MM-DD');
                    data.searchField = $('#searchField').val();
                },
            },
            drawCallback: function() {
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap
                    .Tooltip(tooltipTriggerEl))
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
                    data: 'nama',
                    name: 'nama',
                },
                {
                    data: 'pj',
                    name: 'pj.nama',
                },
                {
                    data: 'anggaran_kegiatan',
                    name: 'anggaran_kegiatan',
                },
                {
                    data: 'kelompok',
                    name: 'kelompok.nama',
                },
                {
                    data: 'subkelompok',
                    name: 'subkelompok.nama',
                },
                {
                    data: 'status',
                    name: 'status.nama',
                },
                {
                    data: 'target_keuangan',
                    name: 'target_keuangan',
                },
                {
                    data: 'realisasi_keuangan',
                    name: 'realisasi_keuangan',
                },
                {
                    data: 'target_fisik',
                    name: 'target_fisik',
                },
                {
                    data: 'realisasi_fisik',
                    name: 'realisasi_fisik',
                },
                {
                    data: 'dones',
                    name: 'dones',
                },
                {
                    data: 'problems',
                    name: 'problems',
                },
                {
                    data: 'follow_up',
                    name: 'follow_up',
                },
                {
                    data: 'todos',
                    name: 'todos',
                },
                {
                    data: 'created_at',
                    name: 'kegiatans.created_at',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [
                [6, 'asc'],
                [15, 'desc'],
            ]
        });
    </script>
@endpush
