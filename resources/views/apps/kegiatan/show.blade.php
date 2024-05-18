@extends('layouts.app-v2')

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
                                    <a href="{{ route('kegiatan-create') }}" class="btn btn-primary me-1">Tambah</a>
                                </div>
                                <div>
                                    <form action="{{ route('excel') }}" method="post" class="me-1">
                                        @csrf
                                        <div>
                                            <button type="submit" class="btn btn-success"
                                                id="excelButton">Excel</button>
                                            <input type="hidden" name="excelDataShow" id="excelDataShow" value="{{ $dataShow->id }}">
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
                                        <th rowspan="3" colspan="1" class="text-center align-middle">Action
                                        </th>

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
                                    <td>{{ $dataShow->nama }}</td>
                                    <td>{{ $dataShow->pj->nama }}</td>
                                    <td>{{ formatRupiahAngka($dataShow->anggaran_kegiatan) }}</td>
                                    <td>{{ $dataShow->kelompok->nama }}</td>
                                    <td>{{ $dataShow->subkelompok->nama }}</td>
                                    <td>{{ $dataShow->status->nama }}</td>
                                    <td>{{ formatRupiahAngka($dataShow->target_keuangan) }}</td>
                                    <td>{{ formatRupiahAngka($dataShow->realisasi_keuangan) }}</td>
                                    <td>{{ $dataShow->target_fisik }}</td>
                                    <td>{{ $dataShow->realisasi_fisik }}</td>
                                    <td>
                                        @foreach ($dataShow->dones as $dones )
                                            <p>{{ $loop->iteration}}. {{ $dones }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($dataShow->problems as $problems )
                                            <p>{{ $loop->iteration}}. {{ $problems }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($dataShow->follow_up as $follow_up )
                                            <p>{{ $loop->iteration}}. {{ $follow_up }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($dataShow->todos as $todos )
                                            <p>{{ $loop->iteration}}. {{ $todos }}</p>
                                        @endforeach
                                    </td>
                                    <td>{{ $dataShow->created_at }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a class="btn btn-warning mx-1" href="{{ route('kegiatan-edit', $dataShow->id) }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Edit Pengaduan">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('kegiatan-delete', $dataShow->id) }}" method="POST">
                                                @method('delete')
                                                @csrf
                                                <button class="btn btn-danger"
                                                    onclick="return confirm('Apakah anda ingin menghapus pengaduan?')"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                    data-bs-title="Hapus Pengaduan" >
                                                    <i class="bi bi-trash text-body-secondary"></i>
                                                </button>
                                            </form>
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
            console.log($dataShow->id)
        });
    </script> --}}
@endpush