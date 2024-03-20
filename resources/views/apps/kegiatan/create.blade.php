@extends('layouts.app-v2')

@section('content')
    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tambah Data Kegiatan</h5>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="pb-4">
                                    <div
                                        class="flex w-1/2 bg-green-800 rounded-md p-2 mx-auto text-center justify-center items-center">
                                        <span class="text-slate-100 align-middle">{{ session('success') }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Vertical Form -->
                            <form method="POST" action="{{ route('kegiatan-create-submit') }}">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="1" class="form-control" name="status_id"
                                        id="status_id">
                                    {{-- <div class="col-12 mb-2">
                                        <label for="nama" class="form-label">Status Kegiatan</label>
                                        <input type="number" value="1" class="form-control" disabled name="status_id">
                                    </div> --}}
                                    <div class="col-12 mb-2">
                                        <label for="nama" class="form-label">Nama Kegiatan</label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                            id="nama" name="nama" value="{{ old('nama') ?: '' }}">
                                        @error('nama')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="kelompok_id" class="form-label">Kelompok</label>
                                        <select name="kelompok_id" id="kelompok_id"
                                            class="form-select @error('kelompok_id') is-invalid @enderror">
                                            <option selected disabled>Pilih Kelompok</option>
                                            @foreach ($kelompoks as $kelompok)
                                                <option value="{{ $kelompok->id }}" @selected(old('kelompok_id') == $kelompok->id)>
                                                    {{ $kelompok->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('kelompok_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="subkelompok_id" class="form-label">Subkelompok</label>
                                        <select name="subkelompok_id" id="subkelompok_id"
                                            class="form-select @error('subkelompok_id') is-invalid @enderror">
                                            <option selected disabled>Pilih Subkelompok</option>
                                            @foreach ($subkelompoks as $subkelompok)
                                                <option value="{{ $subkelompok->id }}" @selected(old('subkelompok_id') == $subkelompok->id)>
                                                    {{ $subkelompok->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('subkelompok_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="user_id" class="form-label">PJ Kegiatan</label>
                                        <select name="user_id" id="user_id"
                                            class="form-select @error('user_id') is-invalid @enderror">
                                            <option selected disabled>Pilih PJ Kegiatan</option>
                                            @foreach ($pjs as $pj)
                                                <option value="{{ $pj->id }}" @selected(old('user_id') == $pj->id)>
                                                    {{ $pj->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="anggaran_kegiatan" class="form-label">Anggaran Kegiatan</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp.</span>
                                            <input type="text"
                                                class="form-control @error('anggaran_kegiatan') is-invalid @enderror"
                                                id="anggaran_kegiatan" name="anggaran_kegiatan"
                                                value="{{ old('anggaran_kegiatan') ?: '' }}">
                                            @error('anggaran_kegiatan')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="target_keuangan" class="form-label">Target Keuangan</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp.</span>
                                            <input type="text"
                                                class="form-control @error('target_keuangan') is-invalid @enderror"
                                                id="target_keuangan" name="target_keuangan"
                                                value="{{ old('target_keuangan') ?: '' }}">
                                            @error('target_keuangan')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="realisasi_keuangan" class="form-label">Realisasi Keuangan</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp.</span>
                                            <input type="text"
                                                class="form-control @error('realisasi_keuangan') is-invalid @enderror"
                                                id="realisasi_keuangan" name="realisasi_keuangan"
                                                value="{{ old('realisasi_keuangan') ?: '' }}">
                                            @error('realisasi_keuangan')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="target_fisik" class="form-label">Target Fisik</label>
                                        <div class="input-group">
                                            <span class="input-group-text">%</span>
                                            <input type="text"
                                                class="form-control @error('target_fisik') is-invalid @enderror"
                                                id="target_fisik" name="target_fisik"
                                                value="{{ old('target_fisik') ?: '' }}">
                                            @error('target_fisik')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="realisasi_fisik" class="form-label">Realisasi Fisik</label>
                                        <div class="input-group">
                                            <span class="input-group-text">%</span>
                                            <input type="text"
                                                class="form-control @error('realisasi_fisik') is-invalid @enderror"
                                                id="realisasi_fisik" name="realisasi_fisik"
                                                value="{{ old('realisasi_fisik') ?: '' }}">
                                            @error('realisasi_fisik')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="donesRow" class="mb-2">
                                    <div class="row align-items-end" id="donesField">
                                        <div class="col-10 col-md-11">
                                            <label for="donesInput" class="form-label">Kegiatan yang sudah
                                                dilakukan</label>
                                            <input type="text"
                                                class="form-control @error('dones.*') is-invalid @enderror"
                                                id="donesInput" name="dones[0]" value="{{ old('dones.0') ?: '' }}">
                                        </div>
                                        <div class="col-2 col-md-1">
                                            <btn type="button" class="btn btn btn-outline-success" id="donesAddBtn">
                                                <i class="bi bi-plus-circle"></i>
                                            </btn>
                                        </div>
                                        @error('dones.*')
                                            <p class="text-danger">
                                                <small>{{ $message }}</small>
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                                <div id="problemsRow" class="mb-2">
                                    <div class="row align-items-end" id="problemsField">
                                        <div class="col-10 col-md-11">
                                            <label for="problemsInput" class="form-label">Permasalahan</label>
                                            <input type="text"
                                                class="form-control @error('problems.*') is-invalid @enderror"
                                                id="problemsInput" name="problems[0]"
                                                value="{{ old('problems.0') ?: '' }}">
                                        </div>
                                        <div class="col-2 col-md-1">
                                            <btn type="button" class="btn btn btn-outline-success" id="problemsAddBtn">
                                                <i class="bi bi-plus-circle"></i>
                                            </btn>
                                        </div>
                                        @error('problems.*')
                                            <p class="text-danger">
                                                <small>{{ $message }}</small>
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                                <div id="followUpRow" class="mb-2">
                                    <div class="row align-items-end" id="followUpField">
                                        <div class="col-10 col-md-11">
                                            <label for="follow_up" class="form-label">Tindak Lanjut</label>
                                            <input type="text"
                                                class="form-control @error('follow_up.*') is-invalid @enderror"
                                                id="follow_up" name="follow_up[0]"
                                                value="{{ old('follow_up.0') ?: '' }}">
                                        </div>
                                        <div class="col-2 col-md-1">
                                            <btn type="button" class="btn btn btn-outline-success" id="followUpAddBtn">
                                                <i class="bi bi-plus-circle"></i>
                                            </btn>
                                        </div>
                                        @error('follow_up.*')
                                            <p class="text-danger">
                                                <small>{{ $message }}</small>
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                                <div id="todosRow" class="mb-2">
                                    <div class="row align-items-end" id="todosField">
                                        <div class="col-10 col-md-11">
                                            <label for="todosInput" class="form-label">Kegiatan yang akan
                                                dilakukan</label>
                                            <input type="text"
                                                class="form-control @error('todos.*') is-invalid @enderror"
                                                id="todosInput" name="todos[0]" value="{{ old('todos.0') ?: '' }}">
                                        </div>
                                        <div class="col-2 col-md-1">
                                            <btn type="button" class="btn btn btn-outline-success" id="todosAddBtn">
                                                <i class="bi bi-plus-circle"></i>
                                            </btn>
                                        </div>
                                        @error('todos.*')
                                            <p class="text-danger">
                                                <small>{{ $message }}</small>
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Button --}}
                                <div class="mt-5 mb-2 me-2 text-end">
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form><!-- Vertical Form -->
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#anggaran_kegiatan').on('input', function() {
                $(this).val($(this).val().replace(/\D/g, ''));
                var amount = $(this).val().replace(/[^\d]/g, ''); // Remove non-numeric characters
                if (amount.length > 0) {
                    amount = parseInt(amount, 10); // Convert to integer
                    $(this).val(formatRupiah(amount)); // Format as Rupiah
                }
            });

            $('#target_keuangan').on('input', function() {
                $(this).val($(this).val().replace(/\D/g, ''));
                var amount = $(this).val().replace(/[^\d]/g, ''); // Remove non-numeric characters
                if (amount.length > 0) {
                    amount = parseInt(amount, 10); // Convert to integer
                    $(this).val(formatRupiah(amount)); // Format as Rupiah
                }
            });

            $('#realisasi_keuangan').on('input', function() {
                $(this).val($(this).val().replace(/\D/g, ''));
                var amount = $(this).val().replace(/[^\d]/g, ''); // Remove non-numeric characters
                if (amount.length > 0) {
                    amount = parseInt(amount, 10); // Convert to integer
                    $(this).val(formatRupiah(amount)); // Format as Rupiah
                }
            });

            $('#target_fisik').on('input', function() {
                $(this).val($(this).val().replace(/[^0-9,]/g, ''));
                   
                // Allow only one decimal point
                var inputVal = $(this).val(); 
                var decimalCount = (inputVal.match(/\,/g) || []).length;

                if (decimalCount > 1) {
                    // More than one decimal point found, remove extra
                    var lastIndex = inputVal.lastIndexOf(',');
                    $(this).val(inputVal.substring(0, lastIndex));
                }
            });
            
            $('#realisasi_fisik').on('input', function() {
                $(this).val($(this).val().replace(/[^0-9,]/g, ''));
                   
                // Allow only one decimal point
                var inputVal = $(this).val(); 
                var decimalCount = (inputVal.match(/\,/g) || []).length;

                if (decimalCount > 1) {
                    // More than one decimal point found, remove extra
                    var lastIndex = inputVal.lastIndexOf('.');
                    $(this).val(inputVal.substring(0, lastIndex));
                }
            });


            function formatRupiah(angka) {
                var number_string = angka.toString();
                var split = number_string.split(',');
                var sisa = split[0].length % 3;
                var rupiah = split[0].substr(0, sisa);
                var ribuan = split[0].substr(sisa).match(/\d{1,3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }


            // Handle Dones Field Row
            var i = 0;
            $("#donesAddBtn").click(function() {
                ++i;
                $("#donesRow").append(
                    `<div class="row align-items-end my-2" id="donesField">
                        <div class="col-10 col-md-11">
                            <input type="text" class="form-control" id="dones" name="dones[` + i + `]" value="{{ old('dones.`+ i +`') ?: '' }}">
                        </div>
                        <div class="col-2 col-md-1">
                            <btn type="button" class="btn btn btn-outline-danger" id="donesRemoveBtn">
                                <i class="bi bi-dash-circle"></i>
                            </btn>
                        </div>
                     </div>`
                );
            });
            $(document).on('click', '#donesRemoveBtn', function() {
                $(this).parents('#donesField').remove();
            });

            // Handle Problems Field Row
            var j = 0;
            $("#problemsAddBtn").click(function() {
                ++j;
                $("#problemsRow").append(
                    `<div class="row align-items-end my-2" id="problemsField">
                        <div class="col-10 col-md-11">
                            <input type="text" class="form-control" id="problems" name="problems[` + j + `]" value="{{ old('problems.`+ j +`') ?: '' }}">
                        </div>
                        <div class="col-2 col-md-1">
                            <btn type="button" class="btn btn btn-outline-danger" id="problemsRemoveBtn">
                                <i class="bi bi-dash-circle"></i>
                            </btn>
                        </div>
                     </div>`
                );
            });
            $(document).on('click', '#problemsRemoveBtn', function() {
                $(this).parents('#problemsField').remove();
            });

            // Handle FollowUp Field Row
            var k = 0;
            $("#followUpAddBtn").click(function() {
                ++k;
                $("#followUpRow").append(
                    `<div class="row align-items-end my-2" id="followUpField">
                        <div class="col-10 col-md-11">
                            <input type="text" class="form-control" id="follow_up" name="follow_up[` + k + `]" value="{{ old('follow_up.`+ k +`') ?: '' }}">
                        </div>
                        <div class="col-2 col-md-1">
                            <btn type="button" class="btn btn btn-outline-danger" id="followUpRemoveBtn">
                                <i class="bi bi-dash-circle"></i>
                            </btn>
                        </div>
                     </div>`
                );
            });
            $(document).on('click', '#followUpRemoveBtn', function() {
                $(this).parents('#followUpField').remove();
            });

            // Handle Todos Field Row
            var l = 0;
            $("#todosAddBtn").click(function() {
                ++l;
                $("#todosRow").append(
                    `<div class="row align-items-end my-2" id="todosField">
                        <div class="col-10 col-md-11">
                            <input type="text" class="form-control" id="todos" name="todos[` + l + `]" value="{{ old('todos.`+ l +`') ?: '' }}">
                        </div>
                        <div class="col-2 col-md-1">
                            <btn type="button" class="btn btn btn-outline-danger" id="todosRemoveBtn">
                                <i class="bi bi-dash-circle"></i>
                            </btn>
                        </div>
                     </div>`
                );
            });
            $(document).on('click', '#todosRemoveBtn', function() {
                $(this).parents('#todosField').remove();
            });
        });
    </script>
@endpush
