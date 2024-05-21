@extends('layouts.app-v2')

@section('content')
    <main id="main" class="main">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mr-4 pr-4">Tambah Data User</h4>
                        <form action="{{ route('user-create-submit') }}" method="POST" class="mx-2">
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
                                        {{-- <span class="help-block text-danger fs-6">
                                                {{ $message }}
                                            </span> --}}
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
                                        value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="password" class="form-label">Password</label>
                                </div>
                                <div class="col-7">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        autocomplete="off" required>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-1" id="password_wrap">
                                    <button class="btn" type="button" id="password_btn">
                                        <i class="bi bi-eye-fill" id="show_eye"></i>
                                        <i class="bi bi-eye-slash-fill d-none" id="hide_eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="confirmed_password" class="col form-label">Confirmed Password</label>
                                </div>
                                <div class="col-7">
                                    <input type="password" id="confirmed_password" name="confirmed_password"
                                        class="form-control @error('confirmed_password') is-invalid @enderror" autofocus
                                        autocomplete="off" required>
                                    @error('confirmed_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-1">
                                    <button class="btn" type="button">
                                        <i class="bi bi-eye-fill" id="confirmed_show_eye"></i>
                                        <i class="bi bi-eye-slash-fill d-none" id="confirmed_hide_eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="subkelompok_id" class="col form-label">Subkelompok</label>
                                </div>
                                <div class="col-7">
                                    <select class="form-select @error('subkelompok_id') is-invalid @enderror"
                                        id="subkelompok_id" name="subkelompok_id" required>
                                        <option selected disabled>Pilih Kelompok</option>
                                        @foreach ($subkelompoks as $subkelompok)
                                            @if (old('subkelompok_id') == $subkelompok->id)
                                                <option value="{{ $subkelompok->id }}" selected>{{ $subkelompok->nama }}
                                                </option>
                                            @else
                                                <option value="{{ $subkelompok->id }}">{{ $subkelompok->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('subkelompok_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-3 col-md-2">
                                    <label for="role_id" class="col form-label">Role</label>
                                </div>
                                <div class="col-7">
                                    <select class="form-select @error('role_id') is-invalid @enderror" name="role_id" id="role_id"
                                        required>
                                        <option selected disabled>Pilih Role</option>
                                        @foreach ($roles as $role)
                                            @if (old('role_id') == $role->id)
                                                <option value="{{ $role->id }}" selected>{{ $role->nama }}</option>
                                            @else
                                                <option value="{{ $role->id }}">{{ $role->nama }}</option>
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
                                    <a href="{{ route('user-create') }}"
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

@push('scripts')
    <script>
        // Password Input
        $(document).ready(function(){
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
