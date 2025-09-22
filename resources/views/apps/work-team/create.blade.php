@extends('layouts.app-v2')

@section('title')
    Tim Kerja | E-Monev BBRM Mektan
@endsection

@section('content')
    <main id="main" class="main">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mr-4 pr-4">Tambah Data Tim Kerja</h4>
                        <form action="{{ route('work-team.create-submit') }}" method="POST" class="mx-2">
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
                                    <label for="team_leader" class="col-form-label">Nama Ketua Tim</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" id="team_leader" name="team_leader"
                                        class="form-control @error('team_leader') is-invalid @enderror" autofocus
                                        autocomplete="off" value="{{ old('team_leader') }}" required>
                                    @error('team_leader')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="work_group_id" class="col form-label">Kelompok</label>
                                </div>
                                <div class="col-7">
                                    <select class="form-select @error('subwork_group_id') is-invalid @enderror"
                                        id="work_group_id" name="work_group_id" required>
                                        <option selected disabled>Pilih Kelompok</option>
                                        @foreach ($workGroups as $work_group)
                                            @if (old('work_group_id') == $work_group->id)
                                                <option value="{{ $work_group->id }}" selected>{{ $work_group->name }}
                                                </option>
                                            @else
                                                <option value="{{ $work_group->id }}">{{ $work_group->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('work_group_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>


                            <div class="d-flex justify-content-end">
                                <div class="d-flex">
                                    <a href="{{ route('work-team.index') }}"
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
