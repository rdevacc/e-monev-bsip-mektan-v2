@extends('layouts.app-v2')

@section('content')
    <main id="main" class="main">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mr-4 pr-4">Tambah Data Kelompok</h4>
                        <form action="{{ route('work-group.create-submit') }}" method="POST" class="mx-2">
                            @csrf
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="name" class="col-form-label">Nama</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror" autofocus
                                        autocomplete="off" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="group_leader" class="col-form-label">Nama Ketua Kelompok</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" id="group_leader" name="group_leader"
                                        class="form-control @error('group_leader') is-invalid @enderror" autofocus
                                        autocomplete="off" value="{{ old('group_leader') }}" required>
                                    @error('group_leader')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <div class="d-flex">
                                    <a href="{{ route('work-group.index') }}"
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
