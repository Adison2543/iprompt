@extends('layouts.app')

@section('content')
<head>
    <!-- Import your CSS file here -->
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">

    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet"/>

</head>
<body>
    <?php $regData = \App\CoreFunction\Helper::regData();?>
    <div class="container">
        <div class="row d-flex justify-content-center text-center">
            <h2 class="my-3">Edit Contract</h2>
            <div class="card p-4">
                @if ($contract->type === 'creditor')
                    <p class="fs-2">สัญญา-เจ้าหนี้</p>
                @elseif ($contract->type === 'debtor')
                    <p class="fs-2">สัญญา-ลูกหนี้</p>
                @elseif ($contract->type === 'outdoor')
                    <p class="fs-2">Out Door</p>
                @endif

                <form action="{{ route('update-contract', ['cid' => $contract->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf

                    @if ($contract->type === 'creditor')
                        <input type="hidden" name="contact_type" value="creditor">
                    @elseif ($contract->type === 'debtor')
                        <input type="hidden" name="contact_type" value="debtor">
                    @elseif ($contract->type === 'outdoor')
                        <input type="hidden" name="contact_type" value="outdoor">
                    @endif

                    <div class="row g-3 mb-3 d-flex justify-content-center">
                        <div class="col-auto">
                            <label for="cont_bnum" class="col-form-label"><span class="text-danger">*</span>เลขที่หนังสือ</label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="cont_bnum" id="cont_bnum" value="{{ $contract->book_num }}" class="form-control" readonly required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3 d-flex justify-content-center">
                        <div class="col-auto">
                            <label for="proj_code" class="col-form-label">โครงการ</label>
                        </div>
                        <div class="col-8">

                            <!-- Options -->
                            <select id="selectprojc" name="proj_code" required>
                                @foreach ($projCodes as $projCode)
                                    <option value="{{ $projCode->id }}"
                                        @if ( $projCode->project_code === $contract->project_code )
                                            selected
                                        @endif>{{ $projCode->project_code }} : {{ $projCode->project_name }}</option>
                                @endforeach
                            </select>
                            <script>
                                new SlimSelect({
                                    select: '#selectprojc',
                                    settings: {
                                        closeOnSelect: true,
                                    },
                                })
                            </script>
                        </div>
                        @can('addProjCode')
                            <div class="col-auto">
                                <button type="button" class="btn btn-success" id="addProjCodebtn" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus"></i></button>

                            </div>
                        @endcan
                    </div>

                    <div class="row g-3 mb-3 d-flex justify-content-center">
                        <div class="col-auto">
                            <label for="cont_title" class="col-form-label"><span class="text-danger">*</span>เรื่อง</label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="cont_title" id="cont_title" value="{{ $contract->title }}" class="form-control bg-white" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3 d-flex justify-content-center">
                        <div class="col-auto">
                            <label for="cont_party" class="col-form-label"><span class="text-danger">*</span>คู่สัญญา</label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="cont_party" id="cont_party" value="{{ $contract->party }}" class="form-control bg-white" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3 d-flex justify-content-center">
                        <div class="col-auto">
                            <label for="cont_budget" class="col-form-label">จำนวนเงิน</label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="cont_budget" id="cont_budget" oninput="formatNumber(this)" value="{{ $contract->budget }}" maxlength="11" class="form-control bg-white" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3 d-flex justify-content-center">
                        <div class="col-auto">
                            <label for="dateRange" class="col-form-label"><span class="text-danger">*</span>ระยะเวลาสัญญา</label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="dateRange" id="dateRange" class="form-control dateRangePicker bg-white" value="{{ $contract->time_range }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3 d-flex justify-content-center">
                        <div class="col-auto">
                            <input class="form-check-input" name="recur_toggle" type="checkbox" {{ $contract->recurring ? 'checked' : '' }} value="1" id="recurring">
                            <label for="recurring" class="col-form-label p-0">การเกิดซ้ำ</label>
                        </div>
                        <div class="col-8">
                            <p class="recur_text text-start" style="display: {{ $contract->recurring ? 'none' : 'block' }};"><span class="badge text-bg-primary">ไม่มีการเกิดซ้ำ</span></p>
                            <div class="recur_input" style="display: {{ $contract->recurring ? 'block' : 'none' }};">
                                <div class="row g-3 mb-3 d-flex justify-content-center">
                                    <div class="col-auto">
                                        <label for="selectday" class="col-form-label"><span class="text-danger">*</span>วันที่</label>
                                    </div>

                                    <div class="col-8">
                                        <!-- Options -->
                                        <select id="selectday" name="recur_d[]" multiple>
                                            @for ($i = 1; $i <= 31; $i++)
                                                <option value="{{ $i }}" {{ in_array("$i", $contract->recurring['recur_d'] ?? []) ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                        <script>
                                            new SlimSelect({
                                                select: '#selectday',
                                                settings: {
                                                    minSelected: 0,
                                                    maxSelected: 31,
                                                    hideSelected: true,
                                                    closeOnSelect: false,
                                                    placeholderText: 'เลือกวันที่',
                                                },
                                            })
                                        </script>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3 d-flex justify-content-center">
                                    <div class="col-auto">
                                        <label for="selectmonth" class="col-form-label"><span class="text-danger">*</span>เดือน</label>
                                    </div>
                                    <div class="col-8">
                                        <!-- Options -->
                                        <select id="selectmonth" name="recur_m[]" multiple >
                                            @for ($i = 1; $i <= 12; $i++)
                                                @php
                                                    $date = \Carbon\Carbon::create()->day(1)->month($i)->year(2022); // Replace 2022 with the desired year
                                                    $thaiMonth = $date->locale('th')->monthName;
                                                @endphp
                                                <option value="{{ $i }}" {{ in_array("$i", $contract->recurring['recur_m'] ?? []) ? 'selected' : '' }}>{{ $thaiMonth }}</option>
                                            @endfor
                                        </select>
                                        <script>
                                            new SlimSelect({
                                                select: '#selectmonth',
                                                settings: {
                                                    minSelected: 0,
                                                    maxSelected: 31,
                                                    hideSelected: true,
                                                    closeOnSelect: false,
                                                    placeholderText: 'เลือกเดือน',
                                                },
                                            })
                                        </script>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3 d-flex justify-content-center">
                                    <div class="col-auto">
                                        <label for="recur_y" class="col-form-label">ทุก</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="number" name="recur_y" id="recur_y" value="{{ $contract->recurring['recur_y'] ?? 1 }}" min="1" max="100" class="form-control bg-white" required>
                                    </div>
                                    <div class="col-auto">
                                        <p>ปี</p>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3 d-flex justify-content-center">
                                    <div class="col-auto">
                                        <label for="recur_count" class="col-form-label">จำนวน</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="number" name="recur_count" value="{{ $contract->recurring['recur_count'] ?? '' }}" id="recur_count" placeholder="ไม่จำเป็นต้องกรอก"  min="1" max="100" class="form-control bg-white">
                                    </div>
                                    <div class="col-auto">
                                        <p>ครั้ง , งวด</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3 d-flex justify-content-center">
                        <div class="col-auto">
                            <label for="cont_note" class="col-form-label">หมายเหตุ</label>
                        </div>
                        <div class="col-8">
                            <textarea class="form-control" name="cont_note" id="cont_note" rows="3">{!! $contract->note !!}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <p class="text-warning" style="font-size: 15px">*Note: หากมีการเปลี่ยนแปลงการเกิดซ้ำ งวดงานเดิมจะถูกลบ</p>
                    </div>
                    <div class="row">
                        <div class="d-flex justify-content-center">
                            <button id="cancel" type="button" class="btn btn-danger ms-2" >ยกเลิก</button>
                            <button type="submit" class="btn btn-success ms-2" >บันทึก</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('add-projcode') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่ม Project Code</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <input type="text" name="projcode" class="form-control mb-2" id="projcode" placeholder="กรอก Project Code ที่ต้องการเพิ่ม" required>
                                    <input type="text" name="projname" class="form-control mb-2" id="projname" placeholder="กรอก Project Name" required>
                                    <p class="text-warning" style="font-size: 12px">*หลังจากบันทึกแล้ว กรุณา roload หน้าเว็บ</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://fastly.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript" src="https://fastly.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://fastly.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://fastly.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://fastly.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
    @if (session('success'))
        Swal.fire({
            title: "Success!",
            text: "You form has been save!",
            icon: "success"
        });
    @elseif (session('error'))
        Swal.fire({
                title: "Error!",
                text: "Something wrong!",
                icon: "error"
            });
            console.log("{{ session('error') }}");
    @endif

    document.getElementById('cancel').addEventListener('click', function () {
        Swal.fire({
            title: 'ยกเลิกการแก้ไขข้อมูล?',
            text: "ข้อมูลของคุณจะไม่ถูกแก้ไข!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ตกลง!',
            cancelButtonText: 'ย้อนกลับ'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route('contTable') }}';
            }
        })
    });

    $(document).ready(function() {
        $('.dateRangePicker').daterangepicker({
            locale: {
                format: 'DD/MM/Y',
                separator: ' - ',
                applyLabel: 'ตกลง',
                cancelLabel: 'ยกเลิก',
                fromLabel: 'จาก',
                toLabel: 'ถึง',
                customRangeLabel: 'กำหนดเอง',
                daysOfWeek: ['อ', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
                monthNames: [
                    'มกราคม',
                    'กุมภาพันธ์',
                    'มีนาคม',
                    'เมษายน',
                    'พฤษภาคม',
                    'มิถุนายน',
                    'กรกฎาคม',
                    'สิงหาคม',
                    'กันยายน',
                    'ตุลาคม',
                    'พฤศจิกายน',
                    'ธันวาคม',
                ],
                firstDay: 1, // Start with Monday
            },
        });
    });

    // Use jQuery for simplicity
    $(document).ready(function() {
        if ($('.enableCheck').is(':checked')) {
            $('.checkdate').prop('disabled', false);
        } else {
            $('.checkdate').prop('disabled', true);
        }

        $('.enableCheck').change(function() {
            // Enable or disable checkboxes with class checkdate based on the checked status of check1
            $('.checkdate').prop('disabled', !$(this).prop('checked'));
        });

        $('#recurring').change(function() {
            if($(this).is(":checked")) {
                $('.recur_input').show();
                $('.recur_text').hide();
                console.log("Checkbox is checked");
            } else {
                $('.recur_input').hide();
                $('.recur_text').show();
                console.log("Checkbox is unchecked");
            }
        });
    });
</script>
</body>

@endsection
