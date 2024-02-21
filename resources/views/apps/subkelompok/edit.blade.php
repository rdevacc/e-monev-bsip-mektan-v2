@extends('layouts.app-v2')

@section('content')
    <main id="main" class="main">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mr-4 pr-4">Edit Data Subkelompok</h4>
                        <form action="{{ route('subkelompok-edit-submit', $subkelompok->id) }}" method="POST" class="mx-2">
                            @method('put')
                            @csrf
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="nama" class="col-form-label">Nama</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" id="nama" name="nama"
                                        class="form-control @error('nama') is-invalid @enderror" autofocus
                                        autocomplete="off" value="{{ old('nama', $subkelompok->nama) }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="nama_katim" class="col-form-label">Nama Ketua Tim</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" id="nama_katim" name="nama_katim"
                                        class="form-control @error('nama_katim') is-invalid @enderror" autofocus
                                        autocomplete="off" value="{{ old('nama_katim', $subkelompok->nama_katim) }}"
                                        required>
                                    @error('nama_katim')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="kelompok_id" class="col form-label">Kelompok</label>
                                </div>
                                <div class="col-7">
                                    <select class="form-select @error('subkelompok_id') is-invalid @enderror"
                                        id="kelompok_id" name="kelompok_id" required>
                                        <option selected disabled>Pilih Subkelompok</option>
                                        @foreach ($kelompoks as $kelompok)
                                            @if (old('kelompok_id', $subkelompok->kelompok_id) == $kelompok->id)
                                                <option value="{{ $kelompok->id }}" selected>{{ $kelompok->nama }}
                                                </option>
                                            @else
                                                <option value="{{ $kelompok->id }}">{{ $kelompok->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('kelompok_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>


                            <div class="d-flex justify-content-end">
                                <div class="d-flex">
                                    <a href="{{ route('subkelompok-create') }}"
                                        class="btn btn-warning text-white"><span>Reset</span></a>
                                </div>
                                <div class="d-flex ps-2">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
