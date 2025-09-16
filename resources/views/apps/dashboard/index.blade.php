@extends('layouts.app-v2')

@push('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
@endpush

@section('content')
<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
          <!-- Left side columns -->
          <div class="col-lg-12">
            <div class="row">
              <!-- Total Kegiatan Card -->
              <div class="col-xxl-3 col-md-6">
                <div class="card info-card total-kegiatan-card">  
                  <div class="card-body">
                    <h5 class="card-title">Total Kegiatan</h5>
  
                    <div class="d-flex align-items-center">
                      <div class="ps-3 d-flex flex-column">
                        <span class="fw-bold fs-5">{{ $jumlahTotalKegiatan }}</span>
                        <span class="text-success small pt-1 fw-bold">Tahun {{ $currentYear }}</span>
                      </div>
                    </div>
                  </div>
  
                </div>
              </div><!-- End Total Kegiatan Card -->
  
              <!-- Total Kegiatan Yang sudah dikerjakan Card -->
              <div class="col-xxl-3 col-md-6">
                <div class="card info-card sudah-dikerjakan-card">
                  <div class="card-body">
                    <h5 class="card-title">Sudah dikerjakan</h5>
                    <div class="d-flex align-items-center">
                      <div class="ps-3 d-flex flex-column">
                        <span class="fw-bold fs-5">{{ $totalSudah }}</span>
                        <span class="text-success small pt-1 fw-bold">Tahun {{ $currentYear }}</span>
                      </div>
                    </div>
                  </div>
  
                </div>
              </div><!-- End Total Kegiatan Yang sudah dikerjakan Card -->
  
              <!-- Total Kegiatan yang Belum dikerkajan Card -->
              <div class="col-xxl-3 col-xl-12">
                <div class="card info-card belum-dikerjakan-card">
                  <div class="card-body">
                    <h5 class="card-title">Belum dikerjakan</h5>
                    <div class="d-flex align-items-center">
                      <div class="ps-3 d-flex flex-column">
                        <span class="fw-bold fs-5">{{ $totalBelum }}</span>
                        <span class="text-success small pt-1 fw-bold">Tahun {{ $currentYear }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!-- End Total Kegiatan yang Belum dikerkajan Card -->
              
              <!-- Total Anggaran Kegiatan Card -->
              <div class="col-xxl-3 col-xl-12">
                <div class="card info-card total-anggaran-card">
                  <div class="card-body">
                    <h5 class="card-title">Total Anggaran Kegiatan</h5>
                    <div class="d-flex align-items-center">
                      <div class="ps-3 d-flex flex-column">
                        <span class="fw-bold fs-5">Rp. {{ formatRupiahAngka($totalAnggaran) }}</span>
                        <span class="text-success small pt-1 fw-bold">Tahun {{ $currentYear }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!-- End Total Anggaran Kegiatan Card -->
            </div>
          </div><!-- End Left side columns -->
        </div>

          <div class="row pb-4">
              <div class="col-lg-12">
                  <!-- Total Kegiatan Chart -->
                  <div class="p-6 m-20 bg-white rounded shadow">
                      {!! $chart->container() !!}
                  </div>     
              </div>
          </div>
          
          <div class="row pb-4">
              <div class="col-lg-12">
                  <!-- Total Kegiatan Sudah Chart -->
                  <div class="p-6 m-20 bg-white rounded shadow">
                      {!! $chartSudahdanBelum->container() !!}
                  </div>     
              </div>
          </div>
      </div>
    </section>
</main>
@endsection

@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    {{-- Script for call Charts --}}
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}

    <script src="{{ $chartSudahdanBelum->cdn() }}"></script>
    {{ $chartSudahdanBelum->script() }}

@endpush()