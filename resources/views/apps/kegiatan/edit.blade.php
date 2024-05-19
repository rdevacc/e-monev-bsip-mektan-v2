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
                            <form method="POST" action="{{ route('kegiatan-edit-submit', $dataEdit->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <label for="status_id" class="form-label">Status Kegiatan</label>
                                        <select name="status_id" id="status_id"
                                            class="form-select @error('status_id') is-invalid @enderror">
                                            <option selected disabled>Pilih status</option>
                                            @foreach ($status_kegiatan as $status)
                                                @if (old('status_id', $dataEdit->status_id) == $status->id)
                                                    <option value="{{ $status->id }}" selected>
                                                        {{ $status->nama }}
                                                    </option>
                                                @else
                                                    <option value="{{ $status->id }}">{{ $status->nama }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('status_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="nama" class="form-label">Nama Kegiatan</label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                            id="nama" name="nama"
                                            value="{{ $dataEdit->nama ? $dataEdit->nama : old('nama') }}">
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
                                                @if (old('kelompok_id', $dataEdit->kelompok_id) == $kelompok->id)
                                                    <option value="{{ $kelompok->id }}" selected>
                                                        {{ $kelompok->nama }}
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
                                    <div class="col-12 mb-2">
                                        <label for="subkelompok_id" class="form-label">Subkelompok</label>
                                        <select name="subkelompok_id" id="subkelompok_id"
                                            class="form-select @error('subkelompok_id') is-invalid @enderror">
                                            <option selected disabled>Pilih Subkelompok</option>
                                            @foreach ($subkelompoks as $subkelompok)
                                                @if (old('subkelompok_id', $dataEdit->subkelompok_id) == $subkelompok->id)
                                                    <option value="{{ $subkelompok->id }}" selected>
                                                        {{ $subkelompok->nama }}
                                                    </option>
                                                @else
                                                    <option value="{{ $subkelompok->id }}">{{ $subkelompok->nama }}
                                                    </option>
                                                @endif
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
                                                @if (old('pj_id', $dataEdit->pj->id) == $pj->id)
                                                    <option value="{{ $pj->id }}" selected>
                                                        {{ $pj->nama }}
                                                    </option>
                                                @else
                                                    <option value="{{ $pj->id }}">{{ $pj->nama }}
                                                    </option>
                                                @endif
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
                                                value="{{ $dataEdit->anggaran_kegiatan ? $dataEdit->anggaran_kegiatan : old('anggaran_kegiatan') }}">
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
                                                value="{{ $dataEdit->target_keuangan ? $dataEdit->target_keuangan : old('target_keuangan') }}">
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
                                                value="{{ $dataEdit->realisasi_keuangan ? $dataEdit->realisasi_keuangan : old('realisasi_keuangan') }}">
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
                                                value="{{ $dataEdit->target_fisik ? $dataEdit->target_fisik : old('target_fisik') }}">
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
                                                value="{{ $dataEdit->realisasi_fisik ? $dataEdit->realisasi_fisik : old('realisasi_fisik') }}">
                                            @error('realisasi_fisik')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Dones Row --}}
                                <div id="donesRow" class="mb-2">
                                    <label for="donesInput" class="form-label">Kegiatan yang sudah
                                        dilakukan</label>
                                    @foreach ($dataEdit->dones as $dones)
                                        <div class="row" id="donesField">
                                            <div class="col-11 col-md-11 mb-2" id="donesInputRow">
                                                <input type="text"
                                                    class="form-control @error('dones.*') is-invalid @enderror"
                                                    id="donesInput" name="dones[{{ $loop->iteration - 1 }}]"
                                                    value="{{ $dones ? $dones : old('dones.' . $loop->iteration - 1) }}">
                                            </div>
                                            <div class="col-md-1 justify-content-end">
                                                <btn type="button" class="btn btn btn-outline-danger"
                                                    id="donesRemoveBtn">
                                                    <i class="bi bi-dash-circle"></i>
                                                </btn>
                                            </div>
                                        </div>
                                    @endforeach
                                    @error('dones.*')
                                        <p class="text-danger">
                                            <small>{{ $message }}</small>
                                        </p>
                                    @enderror
                                </div>
                                <div class="row d-flex justify-content-end" id="donesAddButtonRow">
                                    <div class="col-md-1 justify-content-end">
                                        <btn type="button" class="btn btn btn-outline-success" id="donesAddBtn">
                                            <i class="bi bi-plus-circle"></i>
                                        </btn>
                                    </div>
                                </div>

                                {{-- Problems Row --}}
                                <div id="problemsRow" class="mb-2">
                                    <label for="problemsInput" class="form-label">Permasalahan</label>
                                    @foreach ($dataEdit->problems as $problems)
                                        <div class="row" id="problemsField">
                                            <div class="col-11 col-md-11 mb-2" id="problemsInputRow">
                                                <input type="text"
                                                    class="form-control @error('problems.*') is-invalid @enderror"
                                                    id="problemsInput" name="problems[{{ $loop->iteration - 1 }}]"
                                                    value="{{ $problems ? $problems : old('problems.' . $loop->iteration - 1) }}">
                                            </div>
                                            <div class="col-md-1 justify-content-end">
                                                <btn type="button" class="btn btn btn-outline-danger"
                                                    id="problemsRemoveBtn">
                                                    <i class="bi bi-dash-circle"></i>
                                                </btn>
                                            </div>
                                        </div>
                                    @endforeach
                                    @error('problems.*')
                                        <p class="text-danger">
                                            <small>{{ $message }}</small>
                                        </p>
                                    @enderror
                                </div>
                                <div class="row d-flex justify-content-end" id="problemsAddButtonRow">
                                    <div class="col-md-1 justify-content-end">
                                        <btn type="button" class="btn btn btn-outline-success" id="problemsAddBtn">
                                            <i class="bi bi-plus-circle"></i>
                                        </btn>
                                    </div>
                                </div>

                                {{-- Follow Up Row --}}
                                <div id="follow_upRow" class="mb-2">
                                    <label for="follow_upInput" class="form-label">Tindak Lanjut</label>
                                    @foreach ($dataEdit->follow_up as $follow_up)
                                        <div class="row" id="follow_upField">
                                            <div class="col-11 col-md-11 mb-2" id="follow_upInputRow">
                                                <input type="text"
                                                    class="form-control @error('follow_up.*') is-invalid @enderror"
                                                    id="follow_upInput" name="follow_up[{{ $loop->iteration - 1 }}]"
                                                    value="{{ $follow_up ? $follow_up : old('follow_up.' . $loop->iteration - 1) }}">
                                            </div>
                                            <div class="col-md-1 justify-content-end">
                                                <btn type="button" class="btn btn btn-outline-danger"
                                                    id="follow_upRemoveBtn">
                                                    <i class="bi bi-dash-circle"></i>
                                                </btn>
                                            </div>
                                        </div>
                                    @endforeach
                                    @error('follow_up.*')
                                        <p class="text-danger">
                                            <small>{{ $message }}</small>
                                        </p>
                                    @enderror
                                </div>
                                <div class="row d-flex justify-content-end" id="follow_upAddButtonRow">
                                    <div class="col-md-1 justify-content-end">
                                        <btn type="button" class="btn btn btn-outline-success" id="follow_upAddBtn">
                                            <i class="bi bi-plus-circle"></i>
                                        </btn>
                                    </div>
                                </div>

                                {{-- To Do Row --}}
                                <div id="todosRow" class="mb-2">
                                    <label for="todosInput" class="form-label">Kegiatan yang akan dilakukan</label>
                                    @foreach ($dataEdit->todos as $todos)
                                        <div class="row" id="todosField">
                                            <div class="col-11 col-md-11 mb-2" id="todosInputRow">
                                                <input type="text"
                                                    class="form-control @error('todos.*') is-invalid @enderror"
                                                    id="todosInput" name="todos[{{ $loop->iteration - 1 }}]"
                                                    value="{{ $todos ? $todos : old('todos.' . $loop->iteration - 1) }}">
                                            </div>
                                            <div class="col-md-1 justify-content-end">
                                                <btn type="button" class="btn btn btn-outline-danger"
                                                    id="todosRemoveBtn">
                                                    <i class="bi bi-dash-circle"></i>
                                                </btn>
                                            </div>
                                        </div>
                                    @endforeach
                                    @error('todos.*')
                                        <p class="text-danger">
                                            <small>{{ $message }}</small>
                                        </p>
                                    @enderror
                                </div>
                                <div class="row d-flex justify-content-end" id="todosAddButtonRow">
                                    <div class="col-md-1 justify-content-end">
                                        <btn type="button" class="btn btn btn-outline-success" id="todosAddBtn">
                                            <i class="bi bi-plus-circle"></i>
                                        </btn>
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


            // Checking Input Field
            $(document).ready(function() {
                var dones_len = $("#donesRow input").length;

                if(dones_len == 1) {
                    $("#donesRemoveBtn*").addClass('disabled');
                } else {
                    $("#donesRemoveBtn").removeClass('disabled');
                }
            });
            
            // Checking Problems Input Field
            $(document).ready(function() {
                var problems_len = $("#problemsRow input").length;
               
                if(problems_len == 1) {
                    $("#problemsRemoveBtn*").addClass('disabled');
                } else {
                    $("#problemsRemoveBtn").removeClass('disabled');
                }
            });


            // Handle Dones Field Row
            var dones_len = $("#donesInputRow input").length;
            var i = dones_len
            // Add Dones Button Function
            $("#donesAddBtn").click(function() {
                var dones_len = $("#donesRow input").length;
                console.log(dones_len);
                if(dones_len > 1) {
                    $("#donesRemoveBtn").addClass('disabled');
                } else{
                    $("#donesRemoveBtn").removeClass('disabled');
                }
                ++i;
                $("#donesRow").append(
                    `<div class="row" id="donesField">
                        <div class="col-11 col-md-11 mb-2">
                            <input type="text" class="form-control" id="donesInput" name="dones[`+ i +`]" value="">
                        </div>
                        <div class="col-2 col-md-1">
                            <btn type="button" class="btn btn btn-outline-danger" id="donesRemoveBtn">
                                <i class="bi bi-dash-circle"></i>
                            </btn>     
                        </div>
                     </div>`
                );
            });

            // Remove Dones Button Function
            $(document).on('click', '#donesRemoveBtn', function() {
                var dones_len = $("#donesRow input").length;
                if(dones_len <= 2) {
                    $("#donesRemoveBtn*").addClass('disabled');
                } else{
                    $("#donesRemoveBtn").removeClass('disabled');
                }
                $(this).parents('#donesField').remove();
                console.log(dones_len);
            });

            // Handle Problems Field Row
            var problems_len = $("#problemsInputRow input").length;
            var j = problems_len;
            $("#problemsAddBtn").click(function() {
                ++j;
                $("#problemsRow").append(
                    `<div class="row" id="problemsField">
                        <div class="col-11 col-md-11 mb-2">
                            <input type="text" class="form-control" id="problemsInput" name="problems[`+ j +`]" value="">
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
                var problems_len = $("#problemsRow input").length;
                if(problems_len <= 2) {
                    $("#problemsRemoveBtn*").addClass('disabled');
                } else{
                    $("#problemsRemoveBtn").removeClass('disabled');
                }
                $(this).parents('#problemsField').remove();
                console.log(problems_len);
            });

            // Handle FollowUp Field Row
            var follow_up_len = $("#follow_upInputRow input").length;
            var k = follow_up_len;
            $("#follow_upAddBtn").click(function() {
                ++k;
                $("#follow_upRow").append(
                    `<div class="row" id="follow_upField">
                        <div class="col-11 col-md-11 mb-2">
                            <input type="text" class="form-control" id="follow_upInput" name="follow_up[`+ k +`]" value="">
                        </div>
                        <div class="col-2 col-md-1">
                            <btn type="button" class="btn btn btn-outline-danger" id="follow_upRemoveBtn">
                                <i class="bi bi-dash-circle"></i>
                            </btn>     
                        </div>
                     </div>`
                );
            });
            $(document).on('click', '#follow_upRemoveBtn', function() {
                $(this).parents('#follow_upField').remove();
            });

            // Handle Todos Field Row
            var todos_len = $("#todosInputRow input").length;
            var l = todos_len;
            $("#todosAddBtn").click(function() {
                ++k;
                $("#todosRow").append(
                    `<div class="row" id="todosField">
                        <div class="col-11 col-md-11 mb-2">
                            <input type="text" class="form-control" id="todosInput" name="todos[`+ k +`]" value="">
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
