@extends('layouts.app')

<!-- Scripts -->
<script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/super-build/ckeditor.js"></script>
@vite(['resources/css/form.css' , 'resources/js/form.js'])

@section('content')
<body>
    <div class="text-center my-4">
        <h2>บันทึกข้อตกลงความร่วมมือ</h2>
    </div>
    <form id="myForm" action="{{ route('update') }}" method="POST" >
        @csrf
        <div class="a4-container border mb-5 d-flex align-items-center flex-column">

            <!-- header -->
            <div class="header d-flex flex-column justify-content-center text-center align-items-center">
                <img src="{{ asset('dist/img/logoiddrives.png') }}" height="60">
                <p class="mb-1 fw-bold">บันทึกข้อตกลงความร่วมมือ</p>
                <p class="text-end w-100">เลขที่ {{$form->book_num}}</p>
                    <p class="no-wrap w-100">เรื่อง <input class="ms-2" type="text" name="subject" value="{{$form->title}}" required></p>
                    <p class="mb-0">ระหว่าง</p>
                    <input class="w-50" type="text" id="input1" name="party1" value="{{$form->party1}}" required>
                    <?php $count = 1 ?>
                    @foreach (json_decode($form->parties, true) as $party)
                        <p class="mt-2">และ</p>
                        <input type="text" name="aparty{{$count}}" value="{{$party}}">
                        <?php $count++ ?>
                    @endforeach
                    <div class="w-100" id="inputContainer"></div>
                    <button type="button" class="my-2 btn btn-info" id="addInputButton">เพิ่มข้อมูล</button>
                    <p class="text-danger">*สามารถเพิ่มได้ไม่เกิน 3 ฝ่าย</p>

                    <p class="no-wrap my-2 w-100">บันทึกข้อตกลงฉบับนี้จัดทำขึ้น ณ <input class="ms-2 w-100" type="text" name="location" value="{{$form->place}}" required></p>
            </div> <!-- end header -->

            <!-- content -->
            <div class="content my-4 w-100 h-100 d-flex flex-column">
                    <textarea id="editor" name="myInput" >{!!$form->detail!!}</textarea>
            </div><!-- end content -->
            <!-- footer -->
            <script>let sCount = 0;</script>
            <div class="footer mt-auto">
                <div class="d-flex justify-content-evenly" id="signcontainer" style="flex-wrap:wrap; padding: 0 50px 0 50px;">
                @if ($form->sign)
                    <?php $allSign = $form->sign; ?>
                    @foreach (json_decode($allSign) as $index => $sign)
                        <script> sCount += 1;</script>
                        <div class="p-2">
                            <p class="mb-0">.............................................</p>
                            <p class="mb-0">(<input type="text" name="signname{{$index +1}}" value="{{$sign->signName}}">)</p>
                            <p><input type="text" name="signpos{{$index +1}}" value="{{$sign->signPos}}"></p>
                        </div>
                    @endforeach
                @endif
                </div>
                <button type="button" class="btn btn-primary" id="addsignButton">เพิ่มผู้ลงนาม</button>
                <input type="hidden" id="signCount" name="signCount" value="0">
            </div> <!-- end footer -->

            <!-- send form type for preview -->
            <input type="hidden" name="formtype" id="formtype" value="{{$form->type}}">
            <input type="hidden" name="formid"  value="{{$form->id}}">
        </div>

        <!-- Button -->
        @if ($form->stat !== 'ผ่านการอนุมัติ')
        <div class="d-flex justify-content-center ">
                <a href="#" onclick="goBack()"><button type="button" class="btn btn-secondary">cancle</button></a>
                <button type="submit" id="preview-btn" class="btn btn-success ms-2">Save</button>
            <script>
                function goBack() {
                    window.history.go(-1);
                    window.scrollTo(0, 0);
                }

                // Get references to the button and container elements
                const addButton = document.getElementById('addsignButton');
                const container = document.getElementById('signcontainer');
                const signCount = document.getElementById('signCount');
                signCount.value = sCount;
                // Function to add content to the container
                function addContent() {
                    sCount += 1;
                    const content = `
                        <div class="p-2">
                            <p class="mb-0">.............................................</p>
                            <p class="mb-0">(<input type="text" name="signname${sCount}" placeholder="ชื่อ">)</p>
                            <p><input type="text" name="signpos${sCount}" placeholder="ตำแหน่ง"></p>
                        </div>
                    `;
                    container.innerHTML += content;
                    signCount.value = sCount;
                }

                // Add a click event listener to the button
                addButton.addEventListener('click', addContent);
            </script>
        </div>
        @endif
    </form>
</body>



@endsection