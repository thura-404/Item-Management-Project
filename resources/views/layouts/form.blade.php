@extends('layouts.app')

@section('title')
@lang('public.item')
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
@yield('form-nav-title')
@endsection

@section('nav-item-link')
@yield('form-nav-item-link')
@endsection

@section('nav-item-text')
@yield('form-nav-item-text')
@endsection

@section('body-container')
<!-- Begin Page Content -->
<div class="container-fluid">
    @if($errors->any())
    <div class="card mb-4 py-3 border-bottom-danger alert alert-light alert-dismissible fade show" role="alert">
        <strong class="text-danger">@lang('public.error')!</strong>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session('success'))
    <div class="card mb-4 py-3 border-bottom-success alert alert-light alert-dismissible fade show" role="alert">
        <strong class="text-success">@lang('public.success')!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @yield('form-switch')

    <div class="p-1">
        <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">@lang('public.enterItemDetailis')</h1>

        </div>
        <form class="user form-floating" action="@yield('form-action')" method="post" enctype="multipart/form-data">
            @csrf @yield('form-method')
            <div class="file-upload mb-2">
                <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">@lang('public.addImage')</button>

                <div class="image-upload-wrap" @yield('image-display-none')>
                    <input class="file-upload-input" type='file' name="filImage" onchange="readURL(this);" accept="image/*" />
                    <div class="drag-text">
                        <h3>@lang('public.dragAndDropImage')</h3>
                    </div>
                </div>
                <div class="file-upload-content" @yield('image-display-block')>
                    <img class="file-upload-image" @yield('image-value') src="" alt="your image" />
                    <!-- Hidden input field to track removal -->
                    <input type="hidden" name="remove_image" id="removeImageFlag" value="">
                    <div class="image-title-wrap">
                        <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <div class="form-floating">
                        <input type="text" class="form-control form-control-user" name="txtItemID" id="floatingInputGridId" value="@yield('item-id')" readonly placeholder="Item ID">
                        <label for="floatingInputGridId">@lang('public.itemId')</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-floating">
                        <input type="text" class="form-control form-control-user" name="txtCode" id="floatingInputGridCode" @yield('code-value') value="{{ old('txtCode') }}" @yield('read-only') placeholder="Item Code">
                        <label for="floatingInputGridCode">@lang('public.itemCode')</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-user" name="txtName" id="floatingInputGridName" @yield('name-value') value="{{ old('txtName') }}" @yield('read-only') placeholder="Item Name">
                    <label for="floatingInputGridName">@lang('public.itemName')</label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <div class="form-floating">
                        <input type="number" class="form-control form-control-user" name="txtStock" min="0" max="99999" id="floatingInputGridStock" @yield('stock-value') value="{{ old('txtStock') }}" @yield('read-only') placeholder="Safety Stock">
                        <label for="floatingInputGridStock">@lang('public.safetyStock')</label>
                    </div>
                </div>
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <div class="form-floating">
                        <input type="date" class="form-control form-control-user" name="txtDate" id="floatingInputGridDate" @yield('date-value') value="{{ old('txtDate') }}" @yield('read-only') placeholder="Recieve Date">
                        <label for="floatingInputGridDate">@lang('public.receivedDate')</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <div class="form-floating">
                        <select class="form-select form-control-user-1" name="cbocategories" id="cbocategories" @yield('read-only') aria-label="Floating label select example">
                            @yield('categories')
                        </select>
                        <label for="cboCategories">@lang('public.category')</label>
                    </div>
                </div>
                <div class="col-sm-1">
                    <!-- <input type="text" class="form-control form-control-user" id="exampleRepeatPassword" disabled style="font-size:x-large;" placeholder="&#43;"> -->

                    <!-- Button trigger modal -->
                    <button type="button" class="form-control form-control-user d-flex align-items-center justify-content-center" data-toggle="modal" @yield('read-only') data-target="#saveCategory">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="col-sm-1">
                    <button type="button" id="btnMinus" class="form-control form-control-user d-flex align-items-center justify-content-center" @yield('read-only') data-toggle="modal" data-target="#deleteCategory">
                        <i class="fas fa-minus"></i>
                    </button>
                    <!-- <input type="text" class="form-control form-control-user" id="exampleRepeatPassword" disabled style="font-size:x-large;" placeholder="&#8722"> -->
                </div>
            </div>
            <div class="form-group">
                <div class="form-floating">
                    <textarea type="text" name="txtDescription" class="form-control form-control-user" id="floatingInputGridEmail" cols="30" rows="4" @yield('read-only') placeholder="Description">@yield('description-value'){{ old('txtDescription') }}</textarea>
                    <label for="floatingInputGridEmail">@lang('public.description')</label>
                </div>
            </div>
            <input type="submit" class="btn btn-primary btn-user btn-block" name="btnSave" value="@lang('public.save')">
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
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('public.addNewCategory')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="txtCategory" id="CategoryName" placeholder="@lang('public.category')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="saveCategoryButton">@lang('public.save')</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('public.close')</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('public.deletableCategories')</h5>
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
                            <button class="btn btn-outline-secondary" type="button" id="deleteCategoryButton">@lang('public.delete')</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('public.close')</button>
            </div>
        </div>
    </div>
</div>


@section('js')

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

    var src = $('.file-upload-image').attr('src');
    if (src === null || src === "") {
        removeUpload();
    }

    function removeUpload() {
        $('.file-upload-input').replaceWith($('.file-upload-input').clone());
        $('.file-upload-input').val('');
        $('#removeImageFlag').val(true);
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
            alert("@lang('public.categoryCannotBeEmpty')");
            // Hide the dialog box and clear the input
            $('#saveCategory').hide();
            $('#CategoryName').val('');

            //Remove background backdrop
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            return;
        }

        // Check if category already exists
        var exists = true;
        $('#cbocategories option').each(function() {
            if ($(this).text() == categoryName) {
                exists = false;
                alert("@lang('public.categoryAlreadyExist')");
                return;
            }
        });
        if (exists) {

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
                        if (category.name == categoryName) {
                            var option = $('<option selected>').text(category.name).val(category.id);
                        } else {
                            var option = $('<option>').text(category.name).val(category.id);
                        }
                        $('#cbocategories').append(option);
                    });

                    // Hide the dialog box and clear the input
                    $('#saveCategory').hide();
                    $('#CategoryName').val('');

                    //Remove background backdrop
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();

                    alert("@lang('public.categoryAddedSuccessfully')");
                },
                error: function(xhr, status, error) {

                    // Hide the dialog box and clear the input
                    $('#saveCategory').hide();
                    $('#CategoryName').val('');

                    //Remove background backdrop
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();

                    alert(error);
                }
            });
        }

        // Hide the dialog box and clear the input
        $('#saveCategory').hide();
        $('#CategoryName').val('');

        //Remove background backdrop
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();

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
                alert(error);
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

                alert("@lang('public.categoryDeletedSuccessfully')");
            },
            error: function(xhr, status, error) {
                alert(error);
            }
        });
    });
</script>
@endsection

@endsection