@extends('layouts.app')

<!-- Scripts -->
<script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/super-build/ckeditor.js"></script>
@vite(['resources/css/form.css' , 'resources/js/form.js'])

@section('content')
<head>
    <!-- Import your CSS file here -->
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
</head>
<body onbeforeunload="return myFunction()">

    <div class="text-center my-4">
        <h2>โครงการ</h2>
    </div>
    <form id="myForm" class="overflow-x-auto" action="{{ route('preview') }}" method="POST" >
        @csrf
        <div id="proj-paper" class="a4-container border mb-5 d-flex align-items-center flex-column">
            <!-- header -->
            <div class="header mb-3  text-center ">
                    @if ($class)
                        <p class="text-end mb-0">เอกสารโครงการเลขที่ {{$book_num}}</p>
                        <input type="hidden" name="book_num" value="{{$book_num}}">
                        <p class="text-end">Project Code: {{$projNo}}</p>
                        <input type="hidden" name="projNo" value="{{$projNo}}">
                    @else
                        <p class="text-end">เอกสารโครงการเลขที่ <input type="text" value="PRO0{{$len ?? 0}}/{{ now()->format('Y') + 543 }}" name="book_num" readonly></p>
                        <p class="mb-0 text-end">Project Code: <input class="ms-2" id="projNo" type="text" name="projNo" required></p>
                        <p class="fs-6 text-warning text-end">#กรุณาขอเลข project code จากฝ่ายบัญชี</p>
                    @endif

                    @if ($class)
                        <p class="fw-bold">โครงการ {{$projName}}</p>
                        <input type="hidden" name="projName" value="{{$projName}}">
                    @else
                        <p>ชื่อโครงการ <input class="ms-2 w-100" type="text" name="projName" required></p>
                    @endif
            </div> <!-- end header -->

            <!-- content -->
            <div class="content py-3 w-100 ">
                @if ($class)
                    <?php
                        session_start();
                        $_SESSION['data'] = $editorContent;
                    ?>
                    <div class="editorContent2" style="padding-left:1cm;padding-right:.5cm"> {!! $editorContent !!} </div>
                    <input type="hidden" name="editorContent" value="{{$editorContent}}">
                @else
                    <textarea id="editor" name="myInput" >
                        <?php session_start();?>
                            @if ($_SESSION['data'] ?? false)
                                {!! $_SESSION['data'] !!}
                            @endif
                    </textarea>
                @endif
            </div><!-- end content -->
            <script>
                if (document.querySelector("table")) {
                    const tables = document.querySelectorAll("table");
                    tables.forEach(table => {
                        table.classList.add("table-bordered");
                    })
                }
            </script>
            <!-- footer -->
            <div class="footer mt-auto">
                <div class="d-flex justify-content-evenly">
                    <div class="p-2 border border-black">
                        <p>ผู้จัดทำ/ผู้เสนอโครงการ</p>
                        <br>
                        <p class="no-wrap d-block">(
                            @if ($class)
                                {{$proj_subm}}
                                <input type="hidden" name="proj_subm" value="{{$proj_subm}}">
                            @else
                            <input type="text" name="proj_subm">
                            @endif
                            )</p>
                    </div>
                    <div class="p-2 border border-black">
                        <p>ผู้ตรวจสอบโครงการ</p>
                        <br>
                        <p class="no-wrap d-block">(
                            @if ($class)
                                {{$proj_ins}}
                                <input type="hidden" name="proj_ins" value="{{$proj_ins}}">
                            @else
                            <input type="text" name="proj_ins">
                            @endif
                            )</p>
                    </div>
                    <div class="p-2 border border-black text-center">
                        <p>ผู้อนุมัติโครงการ</p>
                        <br>
                        <p class="no-wrap d-block">(
                            @if ($class)
                                {{$proj_app}}
                                <input type="hidden" name="proj_app" value="{{$proj_app}}">
                            @else
                            <input type="text" name="proj_app">
                            @endif
                            )</p>
                    </div>
                </div>
            </div> <!-- end footer -->

            <!-- send form type for preview -->
            <input type="hidden" name="formtype" id="formtype" value="projForm">
        </div>
        <div class="d-flex justify-content-center ">
            @if ($class)
                <button type="button" class="btn btn-secondary" id="backButton">Back</button>
                <button type="submit" class="btn btn-success ms-2" name="submit" value="save">Save</button>
            @else
                <a href="{{ route('home') }}"><button type="button" class="btn btn-secondary">cancle</button></a>
                <button type="submit" id="preview-btn" class="btn btn-success ms-2" name="submit" value="preview">Preview</button>
            @endif
            <script>
                function goBack() {
                    window.history.back();
                };
                document.getElementById('backButton').addEventListener('click', goBack);

                function myFunction() {
                    return "ตรวจสอบให้แน่ใจว่าคุณต้องการออกจากหน้านี้";
                }
            </script>
        </div>
    </form>
</body>



@endsection
