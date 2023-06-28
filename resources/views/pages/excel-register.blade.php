@extends('layouts.app')

@section('title')
Items
@endsection

@section('link')
<script src="https://kit.fontawesome.com/4d4b71a7a5.js" crossorigin="anonymous"></script>
@endsection

@section('cs-style')
<style>
    .file-upload {
        background-color: #ffffff;
        width: 100%;
        margin: 0 auto;
        padding: 20px;
    }

    .file-upload-btn {
        width: 100%;
        margin: 0;
        color: #fff;
        background: #9FA6B2;
        border: none;
        padding: 10px;
        border-radius: 4px;
        border-bottom: 4px solid #332D2D;
        transition: all .2s ease;
        outline: none;
        text-transform: uppercase;
        font-weight: 700;
    }

    .file-upload-btn:hover {
        background: #332D2D;
        color: #ffffff;
        transition: all .2s ease;
        cursor: pointer;
    }

    .file-upload-btn:active {
        border: 0;
        transition: all .2s ease;
    }

    .file-upload-content {
        display: none;
        text-align: center;
    }

    .file-upload-input {
        position: absolute;
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        outline: none;
        opacity: 0;
        cursor: pointer;
    }

    .image-upload-wrap {
        margin-top: 20px;
        border: 4px dashed #9FA6B2;
        position: relative;
    }

    .image-dropping,
    .image-upload-wrap:hover {
        background-color: #9FA6B2;
        border: 4px dashed #ffffff;
    }

    .image-title-wrap {
        padding: 0 15px 15px 15px;
        color: #222;
    }

    .drag-text {
        text-align: center;
    }

    .drag-text h3 {
        font-weight: 100;
        text-transform: uppercase;
        color: #332D2D;
        padding: 60px 0;
    }

    .file-upload-image {
        max-height: 25rem;
        max-width: auto;
        margin: auto;
        padding: 20px;
    }

    .remove-image {
        width: 200px;
        margin: 0;
        color: #fff;
        background: #cd4535;
        border: none;
        padding: 10px;
        border-radius: 4px;
        border-bottom: 4px solid #b02818;
        transition: all .2s ease;
        outline: none;
        text-transform: uppercase;
        font-weight: 700;
    }

    .remove-image:hover {
        background: #c13b2a;
        color: #ffffff;
        transition: all .2s ease;
        cursor: pointer;
    }

    .remove-image:active {
        border: 0;
        transition: all .2s ease;
    }
</style>
@endsection


@section('body-id')
page-top
@endsection

@section('nav-title')
Item Register
@endsection

@section('nav-item-link')
{{ route('items.register-form') }}
@endsection

@section('nav-item-text')
Register Manually
@endsection

@section('body-container')
<!-- Begin Page Content -->
<div class="container-fluid">
    @if($errors->any())
    <div class="card mb-4 py-3 border-bottom-danger" id="error-message">
        <div class="card-body">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @if (session('success'))
    <div class="card mb-4 py-3 border-bottom-success" id="success-message">
        <div class="card-body">
            {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <a href="{{ route('items.register-form') }}" class="btn btn-secondary">
            <input type="radio" name="options" id="option1" autocomplete="off"> Register Manually
        </a>
        <a href="{{ route('items.excel-form') }}" class="btn btn-secondary active">
            <input type="radio" name="options" id="option2" autocomplete="off" checked> Add Excel File
        </a>
    </div>
    <div class="p-1">
        <div class="text-center">
            <div class="row d-flex ">
                <div class="col-7 d-flex justify-content-end">
                    <h1 class="h4 text-gray-900 mb-4">Excel Upload</h1>
                </div>
                <div class="col-5 d-flex flex-row-reverse">
                    <div class="row">
                        <div class="col">
                            <a href="{{ route('items.excel-format') }}" class="btn btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fa-solid fa-file-arrow-down"></i>
                                </span>
                                <span class="text">Download Excel Format</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <form class="user" action="{{ route('items.excel-register') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="file-upload mb-2">
                <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Add Excel File</button>

                <div class="image-upload-wrap">
                    <input class="file-upload-input" type='file' name="filExcel" onchange="readURL(this);" />
                    <div class="drag-text">
                        <h3>Drag and drop a file or select excel file</h3>
                    </div>
                </div>
                <div class="file-upload-content">
                    <img class="file-upload-image" src="#" alt="your image" />
                    <div class="image-title-wrap">
                        <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
                    </div>
                </div>
            </div>
            <input type="submit" class="btn btn-primary btn-user btn-block" name="btnSave" value="Save Items">
        </form>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->





<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    function readURL(input) {
        if (input.files && input.files[0]) {

            var reader = new FileReader();

            reader.onload = function(e) {
                $('.image-upload-wrap').hide();

                $('.file-upload-content').show();

                $('.image-title').html(input.files[0].name);
            };

            reader.readAsDataURL(input.files[0]);

        } else {
            removeUpload();
        }
    }

    function removeUpload() {
        $('.file-upload-input').replaceWith($('.file-upload-input').clone());
        $('.file-upload-content').hide();
        $('.image-upload-wrap').show();
    }
    $('.image-upload-wrap').bind('dragover', function() {
        $('.image-upload-wrap').addClass('image-dropping');
    });
    $('.image-upload-wrap').bind('dragleave', function() {
        $('.image-upload-wrap').removeClass('image-dropping');
    });
</script>

@endsection