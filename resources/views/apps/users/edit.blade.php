@extends('layouts.app-v2')

@section('title')
    Users | E-Monev BBRM Mektan
@endsection

@section('content')
    <main id="main" class="main">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mr-4 pr-4">Edit Data User</h4>
                        <form action="{{ route('user.edit-submit', $user->id) }}" method="POST" class="mx-2">
                            @csrf
                            @method('PUT')
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="name" class="col-form-label">Nama</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror" autofocus
                                        autocomplete="off" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="email" class="col-form-label">Email</label>
                                </div>
                                <div class="col-7">
                                    <input type="text" id="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror" autocomplete="off"
                                        value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="work_team_id" class="col form-label">Subkelompok</label>
                                </div>
                                <div class="col-7">
                                    <select class="form-select @error('work_team_id') is-invalid @enderror"
                                        name="work_team_id" required>
                                        <option value="{{ $user->work_team_id }}">{{ $user->work_team->name }}</option>
                                        @foreach ($work_teams as $work_team)
                                            @if (old('work_team_id', $user->work_team_id) == $work_team->id)
                                                <option value="{{ $work_team->id }}" selected>
                                                    {{ $work_team->name }}
                                                </option>
                                            @else
                                                <option value="{{ $work_team->id }}">{{ $work_team->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('work_team_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="role" class="col form-label">Role</label>
                                </div>
                                <div class="col-7">
                                    <select class="form-select @error('role_id') is-invalid @enderror" name="role_id"
                                        required>
                                        <option selected disabled>Pilih Role</option>
                                        @foreach ($roles as $role)
                                            @if (old('role_id', $user->role->id) == $role->id)
                                                <option value="{{ $role->id }}" selected>{{ $role->name }}</option>
                                            @else
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>


                            <div class="d-flex justify-content-end">
                                <div class="d-flex">
                                    <a href="{{ route('user.index') }}"
                                        class="btn btn-warning text-white"><span>Kembali</span></a>
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

@push('scripts')
    <script src="{{ asset('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Password Input
        $('#show_eye').on('click', function() {
            togglePassword();
        });

        $('#hide_eye').on('click', function() {
            togglePassword();
        });

        // Retype Password Input
        $('#confirmed_show_eye').on('click', function() {
            toggleRePassword();
        });

        $('#confirmed_hide_eye').on('click', function() {
            toggleRePassword();
        });

        function togglePassword() {
            const passwordInput = $('#password');

            if (passwordInput[0].type === "password") {
                console.log('click show eye');
                $('#hide_eye').removeClass('d-none');
                $('#show_eye').addClass('d-none');
                $('#password').attr('type', 'text');
            } else {
                console.log('click hide eye');
                $('#show_eye').removeClass('d-none');
                $('#hide_eye').addClass('d-none');
                $('#password').attr('type', 'password');
            }
        }

        function toggleRePassword() {
            const passwordInput = $('#confirmed_password');

            if (passwordInput[0].type === "password") {
                console.log('click confirmed show eye');
                $('#confirmed_hide_eye').removeClass('d-none');
                $('#confirmed_show_eye').addClass('d-none');
                $('#confirmed_password').attr('type', 'text');
            } else {
                console.log('click confirmed hide eye');
                $('#confirmed_show_eye').removeClass('d-none');
                $('#confirmed_hide_eye').addClass('d-none');
                $('#confirmed_password').attr('type', 'password');
            }
        }
    </script>
@endpush
