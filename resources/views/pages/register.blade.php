@extends('layouts.app')

@section('title')
Items
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

    .form-control-user-1 {
        font-size: 0.8rem;
        border-radius: 10rem;
        padding: .5rem 1rem;
        height: 3rem;
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
{{ route('items.excel-form') }}
@endsection

@section('nav-item-text')
Add Excel File
@endsection

@section('body-container')
<!-- Begin Page Content -->
<div class="container-fluid">
    @if($errors->any())
    <div class="card mb-4 py-3 border-bottom-danger" id="error-message">
        <div class="card-body">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
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
        <a href="{{ route('items.register-form') }}" class="btn btn-secondary active">
            <input type="radio" name="options" id="option1" autocomplete="off" checked> Register Manually
        </a>
        <a href="{{ route('items.excel-form') }}" class="btn btn-secondary">
            <input type="radio" name="options" id="option2" autocomplete="off"> Add Excel File
        </a>
    </div>

    <div class="p-1">
        <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Enter Items Details</h1>

        </div>
        <form class="user" action="{{ route('items.register') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="file-upload mb-2">
                <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Add Image</button>

                <div class="image-upload-wrap">
                    <input class="file-upload-input" type='file' name="filImage" onchange="readURL(this);" accept="image/*" />
                    <div class="drag-text">
                        <h3>Drag and drop a file or select add Image</h3>
                    </div>
                </div>
                <div class="file-upload-content">
                    <img class="file-upload-image" src="#" alt="your image" />
                    <div class="image-title-wrap">
                        <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" name="txtItemID" class="form-control form-control-user" id="exampleFirstName" value="{{ $itemId }}" readonly placeholder="Item ID">
                </div>
                <div class="col-sm-6">
                    <input type="text" name="txtCode" class="form-control form-control-user" id="exampleLastName" value="{{ old('txtCode') }}" placeholder="Item Code">
                </div>
            </div>
            <div class="form-group">
                <input type="text" name="txtName" class="form-control form-control-user" id="exampleInputEmail" value="{{ old('txtName') }}" placeholder="Item Name">
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="number" name="txtStock" class="form-control form-control-user" min="0" id="exampleInputPassword" value="{{ old('txtStock') }}" placeholder="Safety Stock">
                </div>
                <div class="col-sm-6">
                    <input type="date" name="txtDate" class="form-control form-control-user" id="exampleRepeatPassword" value="{{ old('txtDate') }}" placeholder="Recieve Date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <select name="cbocategories" id="cbocategories" class="form-control form-control-user-1">
                        <option disabled selected hidden value="">Select Category</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-1">
                    <!-- <input type="text" class="form-control form-control-user" id="exampleRepeatPassword" disabled style="font-size:x-large;" placeholder="&#43;"> -->

                    <!-- Button trigger modal -->
                    <button type="button" class="form-control form-control-user d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#saveCategory">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="col-sm-1">
                    <button type="button" id="btnMinus" class="form-control form-control-user d-flex align-items-center justify-content-center" data-toggle="modal" data-target="#deleteCategory">
                        <i class="fas fa-minus"></i>
                    </button>
                    <!-- <input type="text" class="form-control form-control-user" id="exampleRepeatPassword" disabled style="font-size:x-large;" placeholder="&#8722"> -->
                </div>
            </div>
            <div class="form-group">
                <textarea name="txtDescription" id="exampleInputEmail" class="form-control form-control-user" cols="30" rows="2" placeholder="Description">{{ old('txtDescription') }}</textarea>
            </div>
            <input type="submit" class="btn btn-primary btn-user btn-block" name="btnSave" value="Save Item">
        </form>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal -->
<div class="modal fade" id="saveCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="txtCategory" id="CategoryName" placeholder="Category Name" aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="saveCategoryButton">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Deletable Categories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="">
                    @csrf
                    <div class="input-group mb-3">
                        <select name="cboDelCategories" id="cboDelCategories" class="form-control form-control-user">
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="deleteCategoryButton">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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

                $('.file-upload-image').attr('src', e.target.result);
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
        $('.file-upload-input').val('');
        $('.file-upload-content').hide();
        $('.image-upload-wrap').show();
    }
    $('.image-upload-wrap').bind('dragover', function() {
        $('.image-upload-wrap').addClass('image-dropping');
    });
    $('.image-upload-wrap').bind('dragleave', function() {
        $('.image-upload-wrap').removeClass('image-dropping');
    });

    // Add Category (* On click save button)
    $('#saveCategoryButton').click(function() {
        var categoryName = $('#CategoryName').val();

        // Check if category name is empty
        if (categoryName == '') {
            alert('Category name cannot be empty');
            return;
        }

        // Check if category already exists
        var exists = false;
        $('#cbocategories option').each(function() {
            if ($(this).text() == categoryName) {
                exists = true;
                alert('Category already exists!');
                return;
            }
        });

        // Perform AJAX request to save the category
        $.ajax({
            url: "{{ route('categories.register') }}",
            method: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "name": categoryName
            },
            success: function(response) {

                // Clear the select box options
                $('#cbocategories').empty();

                // Add the new category options to the select box
                $.each(response, function(index, category) {
                    var option = $('<option>').text(category.name).val(category.id);
                    $('#cbocategories').append(option);
                });

                // Hide the dialog box and clear the input
                $('#saveCategory').hide();
                $('#CategoryName').val('');

                //Remove background backdrop
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();


            },
            error: function(xhr, status, error) {

                // Hide the dialog box and clear the input
                $('#saveCategory').hide();
                $('#CategoryName').val('');

                //Remove background backdrop
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                // Show error message
                if (xhr.status == 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorHtml = '<ul>';
                    $.each(errors, function(key, value) {
                        errorHtml += '<li>' + value[0] + '</li>';
                    });
                    errorHtml += '</ul>';

                    $('#error-message').show();
                    $('#error-message .card-body').html(errorHtml);
                    setTimeout(function() {
                        $('#error-message').hide();
                    }, 3000);
                }
            }
        });
    });


    // show deletable categories (On click minus button)
    $('#btnMinus').click(function() {

        $.ajax({
            url: "{{ route('categories.deletable') }}",
            method: "GET",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                //Update the select box with the updated category options
                $('#cboDelCategories').html(response.html);

            },
            error: function(xhr, status, error) {
                // Show error message
                alert('Error', 'Failed to fetch categories');
            }
        });

    });

    // delete category (* On click delete button)
    $('#deleteCategoryButton').click(function() {
        var categoryId = $('#cboDelCategories').val();
        console.log(categoryId);

        // Perform AJAX request to delete the category
        $.ajax({
            url: "{{ route('categories.delete') }}",
            method: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "id": categoryId
            },
            success: function(response) {

                // Clear the select box options
                $('#cbocategories').empty();

                // Add the new category options to the select box
                $.each(response, function(index, category) {
                    var option = $('<option>').text(category.name).val(category.id);
                    $('#cbocategories').append(option);
                });

                // Hide the dialog box and clear the input
                $('#deleteCategory').hide();

                //Remove background backdrop
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            },
            error: function(xhr, status, error) {
                console.log('error');
                console.log(error);
            }
        });
    });
</script>

@endsection