@extends('layouts.app-v2')

@section('content')
<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detail Kegiatan</h5>

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

                        {{-- Activity Utama --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kegiatan</label>
                            <input type="text" class="form-control bg-body-secondary"
                                id="name" name="name" value="{{ old('name', $activity->name) }}" readonly>
                        </div>

                        {{-- Work Group --}}
                        <div class="mb-3">
                            <label for="work_group_id" class="form-label">Kelompok Kerja</label>
                            <input type="text" class="form-control bg-body-secondary" name="work_group_id" value="{{ $activity->work_group->name }}" readonly>
                        </div>

                        {{-- Work Team --}}
                        <div class="mb-3">
                            <label for="work_team_id" class="form-label">Tim Kerja</label>
                            <input type="text" class="form-control bg-body-secondary" name="work_team_id" value="{{ $activity->work_team->name }}" readonly>
                        </div>

                        {{-- PJ --}}
                        <div class="mb-3">
                            <label for="user_id" class="form-label">PJ Kegiatan</label>
                            <input type="text" class="form-control bg-body-secondary" name="user_id" value="{{ $activity->user->name }}" readonly>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label for="status_id" class="form-label">Status</label>
                             <input type="text" class="form-control bg-body-secondary" name="status_id" value="{{ $activity->status->name }}" readonly>
                        </div>

                        {{-- Anggaran Kegiatan --}}
                        <div class="mb-3">
                            <label for="activity_budget" class="form-label">Anggaran Kegiatan</label>
                             <input type="text" class="form-control bg-body-secondary" name="activity_budget" value="Rp {{ number_format($activity->activity_budget, 0, ',', '.') }}" readonly>
                        </div>

                        {{-- Monthly Data --}}
                        <div class="mb-3">
                            <label for="period" class="form-label">Bulan</label>
                            <input type="month" class="form-control bg-body-secondary"
                                id="period" name="period" value="{{ old('period', $activity->period) }}">
                        </div>

                        <div id="monthlyCard" class="card mt-3" style="display: none;">
                            <div class="card-body">
                                <h5 class="card-title">Data Bulanan 
                                    <span id="periodText" style="font-weight: bold; color: #0d6efd;"></span>
                                </h5>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Target Keuangan</label>
                                        <p class="form-control-plaintext" id="financial_target">
                                            Rp {{ number_format($activity->financial_target ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Realisasi Keuangan</label>
                                        <p class="form-control-plaintext" id="financial_realization">
                                            Rp {{ number_format($activity->financial_realization ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Target Fisik (%)</label>
                                        <p class="form-control-plaintext" id="physical_target">
                                            {{ str_replace('.', ',', $activity->physical_target ?? 0) }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Realisasi Fisik (%)</label>
                                        <p class="form-control-plaintext" id="physical_realization">
                                            {{ str_replace('.', ',', $activity->physical_realization ?? 0) }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Dynamic Fields jadi list --}}
                                <div class="mb-3">
                                    <label class="form-label">Kegiatan yang sudah dilakukan</label>
                                    <ul class="list-group" id="completedTasksList">
                                        roup-item">-</li>
                                    </ul>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Permasalahan</label>
                                    <ul class="list-group" id="issuesList">
                                       
                                    </ul>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tindak Lanjut</label>
                                    <ul class="list-group" id="followUpsList">
                                       
                                    </ul>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kegiatan yang akan dilakukan</label>
                                    <ul class="list-group" id="plannedTasksList">
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>

                         <div class="mt-4 text-end">
                             @can('update', $activity)
                                <a href="{{ route('activity.edit', $activity) }}" class="btn btn-warning">Edit</a>
                             @endcan
                             <a href="{{ route('activity.index') }}" class="btn btn-primary">Kembali</a>
                         </div>
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
                const ft = $('#financial_target').text();
                if (ft) $('#financial_target').text("Rp " + formatRupiah(rupiahToNumber(ft)));

                const fr = $('#financial_realization').text();
                if (fr) $('#financial_realization').text("Rp " + formatRupiah(rupiahToNumber(fr)));
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
                    $('#financial_target').text(response.financial_target ? "Rp " + formatRupiah(response.financial_target) : "Rp 0");
                    $('#financial_realization').text(response.financial_realization ? "Rp " + formatRupiah(response.financial_realization) : "Rp 0");
                    $('#physical_target').text(response.physical_target ? response.physical_target.toString().replace('.', ',') : "0");
                    $('#physical_realization').text(response.physical_realization ? response.physical_realization.toString().replace('.', ',') : "0");

                    function updateList(selector, dataArray) {
                        let html = "";

                        // filter elemen valid
                        const filtered = (dataArray || []).filter(val => {
                            if (val === null || val === undefined) return false;
                            const str = val.toString().trim().toLowerCase();
                            if (!str || str === '-' || str === 'null' || str === 'undefined') return false;
                            return true;
                        });

                        if (!filtered.length) {
                            html = "<li class='list-group-item'>-</li>";
                        } else {
                            filtered.forEach((val, index) => {
                                html += `<li class="list-group-item">${index + 1}. ${val}</li>`;
                            });
                        }

                        $(selector).html(html);
                    }

                    updateList("#completedTasksList", response.completed_tasks);
                    updateList("#issuesList", response.issues);
                    updateList("#followUpsList", response.follow_ups);
                    updateList("#plannedTasksList", response.planned_tasks);

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
    });
    </script>
@endpush