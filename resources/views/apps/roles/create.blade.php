@extends('layouts.app-v2')

@section('title')
    Roles | E-Monev BBRM Mektan
@endsection

@section('content')
    <main id="main" class="main">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mr-4 pr-4">Tambah Data Role</h4>
                        <form action="{{ route('role.create-submit') }}" method="POST" class="mx-2">
                            @csrf
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="nama" class="col-form-label">Nama</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" id="nama" name="nama"
                                        class="form-control @error('nama') is-invalid @enderror" autofocus
                                        autocomplete="off" value="{{ old('nama') }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>


                            <div class="d-flex justify-content-end">
                                <div class="d-flex">
                                    <a href="{{ route('role.index') }}"
                                        class="btn btn-warning text-white"><span>Kembali</span></a>
                                </div>
                                <div class="d-flex ps-2">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@endpush