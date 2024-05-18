@extends('layouts.app-v2')

@section('content')
<main id="main" class="main">

    <section class="section dashboard">
        <div class="row">
          <!-- Left side columns -->
          <div class="col-lg-8">
            <div class="row">
              <!-- Total Kegiatan Card -->
              <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">  
                  <div class="card-body">
                    <h5 class="card-title">Total Kegiatan</h5>
  
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-database"></i>
                      </div>
                      <div class="ps-3">
                        <h6>{{ $jumlahTotalKegiatan }}</h6>
                        <span class="text-success small pt-1 fw-bold"></span>
                      </div>
                    </div>
                  </div>
  
                </div>
              </div><!-- End Total Kegiatan Card -->
  
              <!-- Total Kegiatan Yang sudah dikerjakan Card -->
              <div class="col-xxl-4 col-md-6">
                <div class="card info-card revenue-card">
  
                  <div class="card-body">
                    <h5 class="card-title">Sudah dikerjakan</h5>
  
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-database-check"></i>
                      </div>
                      <div class="ps-3">
                        <h6>$3,264</h6>
                        <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>
  
                      </div>
                    </div>
                  </div>
  
                </div>
              </div><!-- End Total Kegiatan Yang sudah dikerjakan Card -->
  
              <!-- Customers Card -->
              <div class="col-xxl-4 col-xl-12">
  
                <div class="card info-card customers-card">
  
                  <div class="card-body">
                    <h5 class="card-title">Belum dikerjakan</h5>
  
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-database-exclamation"></i>
                      </div>
                      <div class="ps-3">
                        <h6>1244</h6>
                        <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>
  
                      </div>
                    </div>
  
                  </div>
                </div>
  
              </div><!-- End Customers Card -->
            </div>
          </div><!-- End Left side columns -->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    <!-- Card Body -->
                    <div class="p-6 m-20 bg-white rounded shadow">
                        {!! $chart->container() !!}
                    </div>                
                </div>
            </div>
        </div>

    </div>
      </section>
</main>
@endsection

@push('scripts')
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endpush()