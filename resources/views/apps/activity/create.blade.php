@extends('layouts.app-v2')

@section('content')
   <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tambah Data Kegiatan</h5>

                            {{-- Error & Success Message --}}
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
                                    <div class="flex w-1/2 bg-green-800 rounded-md p-2 mx-auto text-center justify-center items-center">
                                        <span class="text-slate-100 align-middle">{{ session('success') }}</span>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('activity.create-submit') }}" method="POST">
                                @csrf

                                {{-- Activity Utama --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Kegiatan</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- Work Group --}}
                                <div class="mb-3">
                                    <label for="work_group_id" class="form-label">Kelompok Kerja</label>
                                    <select class="form-select @error('work_group_id') is-invalid @enderror" 
                                        id="work_group_id" name="work_group_id">
                                        <option value="">-- Pilih Kelompok --</option>
                                        @foreach ($workGroupList as $group)
                                            <option value="{{ $group->id }}" 
                                                {{ old('work_group_id') == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('work_group_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Work Team --}}
                                <div class="mb-3">
                                    <label for="work_team_id" class="form-label">Tim Kerja</label>
                                    <select class="form-select @error('work_team_id') is-invalid @enderror" 
                                        id="work_team_id" name="work_team_id">
                                        <option value="">-- Pilih Tim --</option>
                                        @foreach ($workTeamList as $team)
                                            <option value="{{ $team->id }}" 
                                                {{ old('work_team_id') == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('work_team_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- PJ --}}
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">PJ Kegiatan</label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" 
                                        id="user_id" name="user_id">
                                        <option value="">-- Pilih PJ kegiatan --</option>
                                        @foreach ($pjList as $pj)
                                            <option value="{{ $pj->id }}" 
                                                {{ old('user_id') == $pj->id ? 'selected' : '' }}>
                                                {{ $pj->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label for="status_id" class="form-label">Status</label>
                                    <select class="form-select @error('status_id') is-invalid @enderror" 
                                        id="status_id" name="status_id">
                                        <option value="">-- Pilih Status --</option>
                                        @foreach ($statusList as $status)
                                            <option value="{{ $status->id }}" 
                                                {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Anggaran Kegiatan --}}
                                <div class="mb-3">
                                    <label for="activity_budget" class="form-label">Anggaran Kegiatan</label>
                                    <input type="text" 
                                        class="form-control @error('activity_budget') is-invalid @enderror"
                                        id="activity_budget" name="activity_budget"
                                        value="{{ old('activity_budget') }}">
                                    @error('activity_budget')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Monthly Data --}}
                                <div class="mb-3">
                                    <label for="period" class="form-label">Periode (Bulan)</label>
                                    <input type="month" class="form-control @error('period') is-invalid @enderror"
                                        id="period" name="period" value="{{ old('period') }}">
                                    @error('period')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="financial_target" class="form-label">Target Keuangan</label>
                                        <input type="text"
                                            class="form-control @error('financial_target') is-invalid @enderror"
                                            id="financial_target" name="financial_target" value="{{ old('financial_target') }}">
                                        @error('financial_target')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="financial_realization" class="form-label">Realisasi Keuangan</label>
                                        <input type="text"
                                            class="form-control @error('financial_realization') is-invalid @enderror"
                                            id="financial_realization" name="financial_realization" value="{{ old('financial_realization') }}">
                                        @error('financial_realization')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="physical_target" class="form-label">Target Fisik (%)</label>
                                        <input type="text"
                                            class="form-control @error('physical_target') is-invalid @enderror"
                                            id="physical_target" name="physical_target" value="{{ old('physical_target') }}">
                                        @error('physical_target')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="physical_realization" class="form-label">Realisasi Fisik (%)</label>
                                        <input type="text"
                                            class="form-control @error('physical_realization') is-invalid @enderror"
                                            id="physical_realization" name="physical_realization" value="{{ old('physical_realization') }}">
                                        @error('physical_realization')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Completed Tasks --}}
                                <div id="completedTasksRow" class="mb-2">
                                    @php $completed = old('completed_tasks', ['']); @endphp
                                    @foreach ($completed as $index => $task)
                                        <div class="row align-items-end completedTasksField my-2">
                                            <div class="col-10 col-md-11">
                                                <label class="form-label">Kegiatan yang sudah dilakukan</label>
                                                <input type="text" 
                                                    class="form-control @error("completed_tasks.$index") is-invalid @enderror" 
                                                    name="completed_tasks[{{ $index }}]" 
                                                    value="{{ $task }}">
                                            </div>
                                            <div class="col-2 col-md-1">
                                                @if ($loop->first)
                                                    <button type="button" class="btn btn-outline-success" id="completedTasksAddBtn">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-outline-danger completedTasksRemoveBtn">
                                                        <i class="bi bi-dash-circle"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            @error("completed_tasks.$index")
                                                <p class="text-danger"><small>{{ $message }}</small></p>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Issues --}}
                                <div id="issuesRow" class="mb-2">
                                    @php $issues = old('issues', ['']); @endphp
                                    @foreach ($issues as $index => $issue)
                                        <div class="row align-items-end issuesField my-2">
                                            <div class="col-10 col-md-11">
                                                <label class="form-label">Permasalahan</label>
                                                <input type="text" 
                                                    class="form-control @error("issues.$index") is-invalid @enderror" 
                                                    name="issues[{{ $index }}]" 
                                                    value="{{ $issue }}">
                                            </div>
                                            <div class="col-2 col-md-1">
                                                @if ($loop->first)
                                                    <button type="button" class="btn btn-outline-success" id="issuesAddBtn">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-outline-danger issuesRemoveBtn">
                                                        <i class="bi bi-dash-circle"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            @error("issues.$index")
                                                <p class="text-danger"><small>{{ $message }}</small></p>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Follow Ups --}}
                                <div id="followUpsRow" class="mb-2">
                                    @php $followUps = old('follow_ups', ['']); @endphp
                                    @foreach ($followUps as $index => $fu)
                                        <div class="row align-items-end followUpsField my-2">
                                            <div class="col-10 col-md-11">
                                                <label class="form-label">Tindak Lanjut</label>
                                                <input type="text" 
                                                    class="form-control @error("follow_ups.$index") is-invalid @enderror" 
                                                    name="follow_ups[{{ $index }}]" 
                                                    value="{{ $fu }}">
                                            </div>
                                            <div class="col-2 col-md-1">
                                                @if ($loop->first)
                                                    <button type="button" class="btn btn-outline-success" id="followUpsAddBtn">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-outline-danger followUpsRemoveBtn">
                                                        <i class="bi bi-dash-circle"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            @error("follow_ups.$index")
                                                <p class="text-danger"><small>{{ $message }}</small></p>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Planned Tasks --}}
                                <div id="plannedTasksRow" class="mb-2">
                                    @php $planned = old('planned_tasks', ['']); @endphp
                                    @foreach ($planned as $index => $pt)
                                        <div class="row align-items-end plannedTasksField my-2">
                                            <div class="col-10 col-md-11">
                                                <label class="form-label">Kegiatan yang akan dilakukan</label>
                                                <input type="text" 
                                                    class="form-control @error("planned_tasks.$index") is-invalid @enderror" 
                                                    name="planned_tasks[{{ $index }}]" 
                                                    value="{{ $pt }}">
                                            </div>
                                            <div class="col-2 col-md-1">
                                                @if ($loop->first)
                                                    <button type="button" class="btn btn-outline-success" id="plannedTasksAddBtn">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-outline-danger plannedTasksRemoveBtn">
                                                        <i class="bi bi-dash-circle"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            @error("planned_tasks.$index")
                                                <p class="text-danger"><small>{{ $message }}</small></p>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Submit --}}
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('activity.index') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // ===== Helper Functions =====
        function onlyNumber(value) {
            return value.replace(/[^\d]/g, ''); // hapus semua selain angka
        }

        function formatRupiah(angka) {
            var number_string = angka.toString();
            var split = number_string.split(',');
            var sisa = split[0].length % 3;
            var rupiah = split[0].substr(0, sisa);
            var ribuan = split[0].substr(sisa).match(/\d{1,3}/gi);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        function handleCurrencyInput(selector) {
            $(selector).on('input', function() {
                var amount = onlyNumber($(this).val());
                if (amount.length > 0) {
                    $(this).val(formatRupiah(amount));
                } else {
                    $(this).val('');
                }
            });
        }

        function handleDecimalInput(selector, separator = ',') {
            $(selector).on('input', function() {
                $(this).val($(this).val().replace(new RegExp(`[^0-9${separator}]`, 'g'), ''));
                var inputVal = $(this).val();
                var decimalCount = (inputVal.match(new RegExp(`\\${separator}`, 'g')) || []).length;
                if (decimalCount > 1) {
                    var lastIndex = inputVal.lastIndexOf(separator);
                    $(this).val(inputVal.substring(0, lastIndex));
                }
            });
        }


        $(document).ready(function() {
            // Currency fields (Rp, bisa milyar/triliun)
            handleCurrencyInput('#activity_budget');
            handleCurrencyInput('#financial_target');
            handleCurrencyInput('#financial_realization');

            // Decimal fields (%)
            handleDecimalInput('#physical_target', ',');
            handleDecimalInput('#physical_realization', ',');

            // Completed Tasks
            var completedIndex = $("#completedTasksRow .completedTasksField").length - 1;
            $("#completedTasksAddBtn").click(function () {
                ++completedIndex;
                $("#completedTasksRow").append(
                    `<div class="row align-items-end completedTasksField my-2">
                        <div class="col-10 col-md-11">
                            <input type="text" class="form-control" 
                                name="completed_tasks[${completedIndex}]" 
                                value="">
                        </div>
                        <div class="col-2 col-md-1">
                            <button type="button" class="btn btn-outline-danger completedTasksRemoveBtn">
                                <i class="bi bi-dash-circle"></i>
                            </button>
                        </div>
                    </div>`
                );
            });
            $(document).on("click", ".completedTasksRemoveBtn", function () {
                $(this).closest(".completedTasksField").remove();
            });

            // Issues
            var issuesIndex = $("#issuesRow .issuesField").length - 1;
            $("#issuesAddBtn").click(function () {
                ++issuesIndex;
                $("#issuesRow").append(
                    `<div class="row align-items-end issuesField my-2">
                        <div class="col-10 col-md-11">
                            <input type="text" class="form-control" 
                                name="issues[${issuesIndex}]" 
                                value="">
                        </div>
                        <div class="col-2 col-md-1">
                            <button type="button" class="btn btn-outline-danger issuesRemoveBtn">
                                <i class="bi bi-dash-circle"></i>
                            </button>
                        </div>
                    </div>`
                );
            });
            $(document).on("click", ".issuesRemoveBtn", function () {
                $(this).closest(".issuesField").remove();
            });

            // Follow Ups
            var followIndex = $("#followUpsRow .followUpsField").length - 1;
            $("#followUpsAddBtn").click(function () {
                ++followIndex;
                $("#followUpsRow").append(
                    `<div class="row align-items-end followUpsField my-2">
                        <div class="col-10 col-md-11">
                            <input type="text" class="form-control" 
                                name="follow_ups[${followIndex}]" 
                                value="">
                        </div>
                        <div class="col-2 col-md-1">
                            <button type="button" class="btn btn-outline-danger followUpsRemoveBtn">
                                <i class="bi bi-dash-circle"></i>
                            </button>
                        </div>
                    </div>`
                );
            });
            $(document).on("click", ".followUpsRemoveBtn", function () {
                $(this).closest(".followUpsField").remove();
            });

            // Planned Tasks
            var plannedIndex = $("#plannedTasksRow .plannedTasksField").length - 1;
            $("#plannedTasksAddBtn").click(function () {
                ++plannedIndex;
                $("#plannedTasksRow").append(
                    `<div class="row align-items-end plannedTasksField my-2">
                        <div class="col-10 col-md-11">
                            <input type="text" class="form-control" 
                                name="planned_tasks[${plannedIndex}]" 
                                value="">
                        </div>
                        <div class="col-2 col-md-1">
                            <button type="button" class="btn btn-outline-danger plannedTasksRemoveBtn">
                                <i class="bi bi-dash-circle"></i>
                            </button>
                        </div>
                    </div>`
                );
            });
            $(document).on("click", ".plannedTasksRemoveBtn", function () {
                $(this).closest(".plannedTasksField").remove();
            });
        });
    </script>
@endpush
