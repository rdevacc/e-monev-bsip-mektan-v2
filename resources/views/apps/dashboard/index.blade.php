@extends('layouts.app-v2')

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
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-database"></i>
                      </div>
                      <div class="ps-3">
                        <h6>{{ $jumlahTotalKegiatan }}</h6>
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
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-database-check"></i>
                      </div>
                      <div class="ps-3">
                        <h6>{{ $totalSudah }}</h6>
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
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-database-exclamation"></i>
                      </div>
                      <div class="ps-3">
                        <h6>{{ $totalBelum }}</h6>
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
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-currency-dollar"></i>
                      </div>
                      <div class="ps-3">
                        <h6>{{ formatRupiahAngka($totalAnggaran) }}</h6>
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
    {{-- Script for call Charts --}}
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}

    <script src="{{ $chartSudahdanBelum->cdn() }}"></script>
    {{ $chartSudahdanBelum->script() }}

@endpush()