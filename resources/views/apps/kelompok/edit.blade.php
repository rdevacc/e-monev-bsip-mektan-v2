@extends('layouts.app-v2')

@section('content')
    <main id="main" class="main">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mr-4 pr-4">Edit Data Kelompok</h4>
                        <form action="{{ route('kelompok-edit-submit', $kelompok->id) }}" method="POST" class="mx-2">
                            @method('put')
                            @csrf
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="nama" class="col-form-label">Nama</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" id="nama" name="nama"
                                        class="form-control @error('nama') is-invalid @enderror" autofocus
                                        autocomplete="off" value="{{ old('nama', $kelompok->nama) }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="nama_kakel" class="col-form-label">Nama Kepala Kelompok</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" id="nama_kakel" name="nama_kakel"
                                        class="form-control @error('nama_kakel') is-invalid @enderror" autofocus
                                        autocomplete="off" value="{{ old('nama_kakel', $kelompok->nama_kakel) }}" required>
                                    @error('nama_kakel')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="anggaran_kelompok" class="col-form-label">Anggaran Kelompok</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" id="anggaran_kelompok" name="anggaran_kelompok"
                                        class="form-control @error('anggaran_kelompok') is-invalid @enderror" autofocus
                                        autocomplete="off" value="{{ old('anggaran_kelompok', $kelompok->anggaran_kelompok) }}" required>
                                    @error('anggaran_kelompok')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>


                            <div class="d-flex justify-content-end">
                                <div class="d-flex">
                                    <a href="{{ route('kelompok-edit', $kelompok->id) }}"
                                        class="btn btn-warning text-white"><span>Reset</span></a>
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
