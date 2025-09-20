@extends('layouts.app-v2')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Data Kegiatan</h5>

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

                        <form action="{{ route('activity.edit-submit', $activity->id) }}" method="POST" id="editActivityForm">
                            @csrf
                            @method('PUT')

                            {{-- Activity Utama --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Kegiatan</label>
                                <input type="text" class="form-control @cannot('adminUpdate', $activity) bg-body-secondary @endcannot @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $activity->name) }}" @cannot('adminUpdate', $activity) readonly @endcannot>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Work Group --}}
                            <div class="mb-3">
                                <label for="work_group_id" class="form-label">Kelompok Kerja</label>
                                <select class="form-select @cannot('adminUpdate', $activity) bg-body-secondary @endcannot @error('work_group_id') is-invalid @enderror" 
                                    id="work_group_id" name="work_group_id" @cannot('adminUpdate', $activity) disabled @endcannot>
                                    <option value="">-- Pilih Kelompok --</option>
                                    @foreach ($workGroupList as $group)
                                        <option value="{{ $group->id }}" 
                                            {{ old('work_group_id', $activity->work_group_id) == $group->id ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="work_group_id" value="{{ $activity->work_group_id }}">
                                @error('work_group_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Work Team --}}
                            <div class="mb-3">
                                <label for="work_team_id" class="form-label">Tim Kerja</label>
                                <select class="form-select @cannot('adminUpdate', $activity) bg-body-secondary @endcannot @error('work_team_id') is-invalid @enderror" 
                                    id="work_team_id" name="work_team_id" @cannot('adminUpdate', $activity) disabled @endcannot>
                                    <option value="">-- Pilih Tim --</option>
                                    @foreach ($workTeamList as $team)
                                        <option value="{{ $team->id }}" 
                                            {{ old('work_team_id', $activity->work_team_id) == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="work_team_id" value="{{ $activity->work_team_id }}">
                                @error('work_team_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- PJ --}}
                            <div class="mb-3">
                                <label for="user_id" class="form-label">PJ Kegiatan</label>
                                <select class="form-select @cannot('adminUpdate', $activity) bg-body-secondary @endcannot @error('user_id') is-invalid @enderror" 
                                    id="user_id" name="user_id" @cannot('adminUpdate', $activity) disabled @endcannot>
                                    <option value="">-- Pilih PJ kegiatan --</option>
                                    @foreach ($pjList as $pj)
                                        <option value="{{ $pj->id }}" 
                                            {{ old('user_id', $activity->user_id) == $pj->id ? 'selected' : '' }}>
                                            {{ $pj->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @cannot('adminUpdate', $activity)
                                <input type="hidden" name="user_id" value="{{ $activity->user_id }}">
                                @endcannot
                                
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="mb-3">
                                <label for="status_id" class="form-label">Status</label>
                                <select class="form-select @cannot('adminUpdate', $activity) bg-body-secondary @endcannot @error('status_id') is-invalid @enderror" 
                                    id="status_id" name="status_id" @cannot('adminUpdate', $activity) disabled @endcannot>
                                    <option value="">-- Pilih Status --</option>
                                    @foreach ($statusList as $status)
                                        <option value="{{ $status->id }}" 
                                            {{ old('status_id', $activity->status_id) == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="status_id" value="{{ $activity->status_id }}">
                                @error('status_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Anggaran Kegiatan --}}
                            <div class="mb-3">
                                <label for="activity_budget" class="form-label">Anggaran Kegiatan</label>
                                <input type="text" 
                                    class="form-control @cannot('adminUpdate', $activity) bg-body-secondary @endcannot @error('activity_budget') is-invalid @enderror"
                                    id="activity_budget" name="activity_budget"
                                    value="Rp {{ number_format($activity->activity_budget, 0, ',', '.') }}" @cannot('adminUpdate', $activity) readonly @endcannot>
                                @error('activity_budget')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Monthly Data --}}
                            <div class="mb-3">
                                <label for="period" class="form-label">Bulan</label>
                                <input type="month" class="form-control @error('period') is-invalid @enderror"
                                    id="period" name="period" value="{{ old('period', $activity->period) }}">
                                @error('period')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Card Data Bulanan (disembunyikan dulu) --}}
                            <div id="monthlyCard" class="card mt-3" style="display: none;">
                                <div class="card-body">
                                    <h5 class="card-title">Data Bulanan <span id="periodText" style="font-weight: bold; color: #0d6efd;"></span></h5>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="financial_target" class="form-label">Target Keuangan</label>
                                            <input type="text"
                                                class="form-control @error('financial_target') is-invalid @enderror"
                                                id="financial_target" name="financial_target" value="{{ old('financial_target', $activity->financial_target) }}">
                                            @error('financial_target')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="financial_realization" class="form-label">Realisasi Keuangan</label>
                                            <input type="text"
                                                class="form-control @error('financial_realization') is-invalid @enderror"
                                                id="financial_realization" name="financial_realization" value="{{ old('financial_realization', $activity->financial_realization) }}">
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
                                                id="physical_target" name="physical_target" value="{{ old('physical_target', $activity->physical_target) }}">
                                            @error('physical_target')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="physical_realization" class="form-label">Realisasi Fisik (%)</label>
                                            <input type="text"
                                                class="form-control @error('physical_realization') is-invalid @enderror"
                                                id="physical_realization" name="physical_realization" value="{{ old('physical_realization', $activity->physical_realization) }}">
                                            @error('physical_realization')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Dynamic Fields Section --}}
                                    @php
                                        $completed = old('completed_tasks', $activity->completed_tasks ?? ['']);
                                        $issues = old('issues', $activity->issues ?? ['']);
                                        $followUps = old('follow_ups', $activity->follow_ups ?? ['']);
                                        $planned = old('planned_tasks', $activity->planned_tasks ?? ['']);
                                    @endphp

                                    @foreach ([
                                        ['rowId'=>'completedTasksRow','data'=>$completed,'field'=>'completed_tasks','label'=>'Kegiatan yang sudah dilakukan','addBtn'=>'completedTasksAddBtn'],
                                        ['rowId'=>'issuesRow','data'=>$issues,'field'=>'issues','label'=>'Permasalahan','addBtn'=>'issuesAddBtn'],
                                        ['rowId'=>'followUpsRow','data'=>$followUps,'field'=>'follow_ups','label'=>'Tindak Lanjut','addBtn'=>'followUpsAddBtn'],
                                        ['rowId'=>'plannedTasksRow','data'=>$planned,'field'=>'planned_tasks','label'=>'Kegiatan yang akan dilakukan','addBtn'=>'plannedTasksAddBtn'],
                                    ] as $section)
                                        <div id="{{ $section['rowId'] }}" class="mb-2">
                                            @foreach ($section['data'] as $index => $val)
                                                <div class="row align-items-end {{ $section['field'] }}Field my-2">
                                                    <div class="col-10 col-md-11">
                                                        @if($loop->first)<label class="form-label">{{ $section['label'] }}</label>@endif
                                                        <input type="text" 
                                                            class="form-control @error($section['field'].'.'.$index) is-invalid @enderror" 
                                                            name="{{ $section['field'] }}[{{ $index }}]" value="{{ $val }}">
                                                    </div>
                                                    <div class="col-2 col-md-1">
                                                        @if($loop->first)
                                                            <button type="button" class="btn btn-outline-success" id="{{ $section['addBtn'] }}">
                                                                <i class="bi bi-plus-circle"></i>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-outline-danger {{ $section['field'] }}RemoveBtn">
                                                                <i class="bi bi-dash-circle"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                    @error($section['field'].'.'.$index)
                                                        <p class="text-danger"><small>{{ $message }}</small></p>
                                                    @enderror
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('activity.index') }}" class="btn btn-secondary">Batal</a>
                                {{-- Clear button --}}
                                {{-- <button type="button" id="clearMonthlyData" class="btn btn-warning">
                                    Kosongkan Data Bulanan
                                </button> --}}
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
$(document).ready(function() {
    // ===== Helper Functions =====
    function updatePeriodTitle() {
        let val = $('#period').val();
        if (!val) {
            $('#periodText').text('');
            return;
        }

        // val formatnya 'YYYY-MM'
        let [year, month] = val.split('-');
        const monthNames = ["Januari","Februari","Maret","April","Mei","Juni",
                            "Juli","Agustus","September","Oktober","November","Desember"];
        let monthText = monthNames[parseInt(month)-1] + ' ' + year;

        $('#periodText').text(monthText);
        console.log("Selected period:", monthText);
    }

    function formatRupiah(value) {
        if (value === null || value === undefined || value === '') return '';
        // accept either number or string
        let number = Number(value);
        if (isNaN(number)) {
            // try to parse formatted string like "1.234,56" or "Rp 1.234"
            number = rupiahToNumber(String(value));
        }
        number = Math.abs(number);
        let intPart = Math.floor(number).toString();
        let decimalPart = Math.round((number - Math.floor(number)) * 100);
        let decimalStr = decimalPart > 0 ? ',' + decimalPart.toString().padStart(2, '0') : '';
        intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return intPart + decimalStr; // NOTE: no "Rp " prefix to keep input consistent
    }

    function rupiahToNumber(str) {
        if (str === null || str === undefined || String(str).trim() === '') return 0;
        let s = String(str).trim();
        // remove any non-digit, non-dot, non-comma, non-minus characters (removes "Rp", spaces)
        s = s.replace(/[^0-9.,-]/g, '');

        // If both dot and comma present, assume dots are thousands separator and comma decimal
        if (s.indexOf('.') > -1 && s.indexOf(',') > -1) {
            s = s.replace(/\./g, '').replace(/,/g, '.');
        } else if (s.indexOf('.') > -1 && s.indexOf(',') === -1) {
            // only dot present -> treat as thousands separator -> remove dots
            s = s.replace(/\./g, '');
        } else if (s.indexOf(',') > -1 && s.indexOf('.') === -1) {
            // only comma present -> decimal separator -> replace with dot
            s = s.replace(/,/g, '.');
        }
        let n = parseFloat(s);
        return isNaN(n) ? 0 : n;
    }

    function onlyNumber(value) {
        return value.replace(/[^\d]/g, '');
    }

    function handleCurrencyInput(selector) {
        $(document).on('input', selector, function() {
            var raw = $(this).val();
            // keep only digits for building number, then format
            var amount = onlyNumber(raw);
            $(this).val(amount.length ? formatRupiah(amount) : '');
        });
    }

    function handleDecimalInput(selector, separator = ',') {
        $(document).on('input', selector, function() {
            $(this).val($(this).val().replace(new RegExp(`[^0-9${separator}]`, 'g'), ''));
            var inputVal = $(this).val();
            var decimalCount = (inputVal.match(new RegExp(`\\${separator}`, 'g')) || []).length;
            if (decimalCount > 1) {
                $(this).val(inputVal.substring(0, inputVal.lastIndexOf(separator)));
            }
        });
    }

    // ===== Input Formatting Binding =====
    handleCurrencyInput('#activity_budget');
    handleCurrencyInput('#financial_target');
    handleCurrencyInput('#financial_realization');
    handleDecimalInput('#physical_target', ',');
    handleDecimalInput('#physical_realization', ',');

    // ===== Utility: normalize dynamic rows (label only first, add button only first) =====
    function normalizeDynamicRows(rowId, fieldName, fieldLabel, addBtnId) {
        const fieldClass = fieldName + 'Field';
        const removeBtnClass = fieldName + 'RemoveBtn';

        $(rowId).find('.' + fieldClass).each(function(i, el) {
            // rename inputs sequentially
            $(el).find('input').attr('name', fieldName + '[' + i + ']');

            // label only for first
            if (i === 0) {
                if ($(el).find('label.form-label').length === 0) {
                    $(el).find('.col-10').prepend(`<label class="form-label">${fieldLabel}</label>`);
                }
                // make first action button be add (green) with correct id
                $(el).find('.col-2').html(`<button type="button" class="btn btn-outline-success" id="${addBtnId}"><i class="bi bi-plus-circle"></i></button>`);
            } else {
                $(el).find('label.form-label').remove();
                $(el).find('.col-2').html(`<button type="button" class="btn btn-outline-danger ${removeBtnClass}"><i class="bi bi-dash-circle"></i></button>`);
            }
        });
    }

    // ===== Dynamic Fields Handler (uses fieldName like 'completed_tasks') =====
    function setupDynamicField(rowId, addBtnId, fieldName, fieldLabel) {
        const fieldClass = fieldName + 'Field';
        const removeBtnClass = fieldName + 'RemoveBtn';

        function addField(val = '') {
            const newIndex = $(rowId).find('.' + fieldClass).length;
            $(rowId).append(`
                <div class="row align-items-end ${fieldClass} my-2">
                    <div class="col-10 col-md-11">
                        ${newIndex === 0 ? `<label class="form-label">${fieldLabel}</label>` : ''}
                        <input type="text" class="form-control" name="${fieldName}[${newIndex}]" value="${val}">
                    </div>
                    <div class="col-2 col-md-1">
                        ${newIndex === 0 ? `<button type="button" class="btn btn-outline-success" id="${addBtnId}"><i class="bi bi-plus-circle"></i></button>` : `<button type="button" class="btn btn-outline-danger ${removeBtnClass}"><i class="bi bi-dash-circle"></i></button>`}
                    </div>
                </div>
            `);
        }

        // delegated add button (works for dynamic content)
        $(document).on('click', `#${addBtnId}`, function() {
            addField('');
            normalizeDynamicRows(rowId, fieldName, fieldLabel, addBtnId);
        });

        // delegated remove button
        $(document).on('click', `.${removeBtnClass}`, function() {
            $(this).closest('.' + fieldClass).remove();
            // re-index and normalize
            normalizeDynamicRows(rowId, fieldName, fieldLabel, addBtnId);
        });
    }

    // initialize dynamic fields (matching Blade field names)
    setupDynamicField("#completedTasksRow", "completedTasksAddBtn", "completed_tasks", "Kegiatan yang sudah dilakukan");
    setupDynamicField("#issuesRow", "issuesAddBtn", "issues", "Permasalahan");
    setupDynamicField("#followUpsRow", "followUpsAddBtn", "follow_ups", "Tindak Lanjut");
    setupDynamicField("#plannedTasksRow", "plannedTasksAddBtn", "planned_tasks", "Kegiatan yang akan dilakukan");

    // ===== Show monthly card when appropriate =====
    function showMonthlyIfNeeded() {
        const periodVal = $('#period').val();
        const anyMonthlyInput = ($('#financial_target').val() || '').toString().trim()
            || ($('#financial_realization').val() || '').toString().trim()
            || ($('#physical_target').val() || '').toString().trim()
            || ($('#physical_realization').val() || '').toString().trim()
            || $('#completedTasksRow input').filter(function(){ return $(this).val().toString().trim() !== '' }).length > 0
            || $('#issuesRow input').filter(function(){ return $(this).val().toString().trim() !== '' }).length > 0
            || $('#followUpsRow input').filter(function(){ return $(this).val().toString().trim() !== '' }).length > 0
            || $('#plannedTasksRow input').filter(function(){ return $(this).val().toString().trim() !== '' }).length > 0;

        if (periodVal || anyMonthlyInput) {
            $('#monthlyCard').show();
            updatePeriodTitle();
            // format any existing numeric values
            const ft = $('#financial_target').val();
            if (ft) $('#financial_target').val(formatRupiah(rupiahToNumber(ft)));
            const fr = $('#financial_realization').val();
            if (fr) $('#financial_realization').val(formatRupiah(rupiahToNumber(fr)));
        } else {
            $('#monthlyCard').hide();
        }

        // normalize rows to ensure add/remove buttons and labels correct
        normalizeDynamicRows('#completedTasksRow', 'completed_tasks', 'Kegiatan yang sudah dilakukan', 'completedTasksAddBtn');
        normalizeDynamicRows('#issuesRow', 'issues', 'Permasalahan', 'issuesAddBtn');
        normalizeDynamicRows('#followUpsRow', 'follow_ups', 'Tindak Lanjut', 'followUpsAddBtn');
        normalizeDynamicRows('#plannedTasksRow', 'planned_tasks', 'Kegiatan yang akan dilakukan', 'plannedTasksAddBtn');
    }

    // run on load
    showMonthlyIfNeeded();

    // ===== AJAX Update Berdasarkan Periode =====
    $('#period').on('change', function() {
        let period = $(this).val();
        let activityId = "{{ $activity->id }}";
        let url = "{{ route('activity.monthly-data', ':id') }}".replace(':id', activityId);

        if (!period) {
            // jika periode dikosongkan, sembunyikan card (sesuai requirement)
            showMonthlyIfNeeded();
            return;
        }

        $.ajax({
            url: url,
            type: 'GET',
            data: { period: period },
            success: function(response) {
                // safe parsing + formatting
                $('#financial_target').val(response.financial_target ? formatRupiah(rupiahToNumber(response.financial_target)) : '');
                $('#financial_realization').val(response.financial_realization ? formatRupiah(rupiahToNumber(response.financial_realization)) : '');
                $('#physical_target').val(response.physical_target ? response.physical_target.toString().replace('.', ',') : '');
                $('#physical_realization').val(response.physical_realization ? response.physical_realization.toString().replace('.', ',') : '');

                function updateFields(rowId, fieldName, dataArray, fieldLabel, addBtnId) {
                    $(rowId).empty();
                    if (!dataArray || !dataArray.length) dataArray = [''];
                    dataArray.forEach((val, i) => {
                        const fieldClass = fieldName + 'Field';
                        const removeBtnClass = fieldName + 'RemoveBtn';
                        $(rowId).append(`
                            <div class="row align-items-end ${fieldClass} my-2">
                                <div class="col-10 col-md-11">
                                    ${i === 0 ? `<label class="form-label">${fieldLabel}</label>` : ''}
                                    <input type="text" class="form-control" name="${fieldName}[${i}]" value="${val ?? ''}">
                                </div>
                                <div class="col-2 col-md-1">
                                    ${i === 0 ? `<button type="button" class="btn btn-outline-success" id="${addBtnId}"><i class="bi bi-plus-circle"></i></button>` :
                                    `<button type="button" class="btn btn-outline-danger ${removeBtnClass}"><i class="bi bi-dash-circle"></i></button>`}
                                </div>
                            </div>
                        `);
                    });
                    // ensure proper labels/buttons after insertion
                    normalizeDynamicRows(rowId, fieldName, fieldLabel, addBtnId);
                }

                updateFields('#completedTasksRow', 'completed_tasks', response.completed_tasks, 'Kegiatan yang sudah dilakukan', 'completedTasksAddBtn');
                updateFields('#issuesRow', 'issues', response.issues, 'Permasalahan', 'issuesAddBtn');
                updateFields('#followUpsRow', 'follow_ups', response.follow_ups, 'Tindak Lanjut', 'followUpsAddBtn');
                updateFields('#plannedTasksRow', 'planned_tasks', response.planned_tasks, 'Kegiatan yang akan dilakukan', 'plannedTasksAddBtn');

                // tampilkan card bila sukses load data
                $('#monthlyCard').slideDown();
                updatePeriodTitle();
            },
            error: function(xhr) {
                // tampilkan card agar user bisa tetap input manual jika data tidak ditemukan
                $('#monthlyCard').slideDown();
                updatePeriodTitle();
            }
        });
    });

    // ===== Clear Monthly Data =====
    $('#clearMonthlyData').on('click', function() {
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Data bulanan akan dikosongkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, kosongkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Kosongkan input keuangan & fisik
                $('#financial_target').val('');
                $('#financial_realization').val('');
                $('#physical_target').val('');
                $('#physical_realization').val('');

                // Fungsi untuk kosongkan dynamic field - sisakan 1 kosong
                function clearDynamicFields(rowId, fieldName, fieldLabel, addBtnId) {
                    $(rowId).empty();
                    $(rowId).append(`
                        <div class="row align-items-end ${fieldName}Field my-2">
                            <div class="col-10 col-md-11">
                                <label class="form-label">${fieldLabel}</label>
                                <input type="text" class="form-control" name="${fieldName}[0]" value="">
                            </div>
                            <div class="col-2 col-md-1">
                                <button type="button" class="btn btn-outline-success" id="${addBtnId}">
                                    <i class="bi bi-plus-circle"></i>
                                </button>
                            </div>
                        </div>
                    `);
                }

                clearDynamicFields('#completedTasksRow','completed_tasks','Kegiatan yang sudah dilakukan','completedTasksAddBtn');
                clearDynamicFields('#issuesRow','issues','Permasalahan','issuesAddBtn');
                clearDynamicFields('#followUpsRow','follow_ups','Tindak Lanjut','followUpsAddBtn');
                clearDynamicFields('#plannedTasksRow','planned_tasks','Kegiatan yang akan dilakukan','plannedTasksAddBtn');

                Swal.fire(
                    'Berhasil!',
                    'Data bulanan telah dikosongkan.',
                    'success'
                );
            }
        });
    });

    // ===== Form Submit convert Rupiah to number =====
    $('#editActivityForm').on('submit', function() {
        $('#financial_target').val(rupiahToNumber($('#financial_target').val()));
        $('#financial_realization').val(rupiahToNumber($('#financial_realization').val()));

        $('#physical_target').val($('#physical_target').val().trim().replace(/,/g, '.'));
        $('#physical_realization').val($('#physical_realization').val().trim().replace(/,/g, '.'));
    });

});
</script>
@endpush
