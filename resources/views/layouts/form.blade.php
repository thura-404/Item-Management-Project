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

    @yield('form-switch')

    <div class="p-1">
        <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Enter Items Details</h1>

        </div>
        <form class="user" action="@yield('form-action')" method="post" enctype="multipart/form-data">
            @csrf @yield('form-method')
            <div class="file-upload mb-2">
                <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Add Image</button>

                <div class="image-upload-wrap" @yield('image-display-none')>
                    <input class="file-upload-input" type='file' name="filImage" onchange="readURL(this);" accept="image/*" />
                    <div class="drag-text">
                        <h3>Drag and drop a file or select add Image</h3>
                    </div>
                </div>
                <div class="file-upload-content" @yield('image-display-block')>
                    <img class="file-upload-image" @yield('image-value') src="#" alt="your image" />
                    <div class="image-title-wrap">
                        <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" name="txtItemID" class="form-control form-control-user" id="exampleFirstName" value="@yield('item-id')" readonly placeholder="Item ID">
                </div>
                <div class="col-sm-6">
                    <input type="text" name="txtCode" class="form-control form-control-user" id="exampleLastName" @yield('code-value') value="{{ old('txtCode') }}" @yield('read-only') placeholder="Item Code">
                </div>
            </div>
            <div class="form-group">
                <input type="text" name="txtName" class="form-control form-control-user" id="exampleInputEmail" @yield('name-value') value="{{ old('txtName') }}" @yield('read-only') placeholder="Item Name">
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="number" name="txtStock" class="form-control form-control-user" min="0" id="exampleInputPassword" @yield('stock-value') value="{{ old('txtStock') }}" @yield('read-only') placeholder="Safety Stock">
                </div>
                <div class="col-sm-6">
                    <input type="date" name="txtDate" class="form-control form-control-user" id="exampleRepeatPassword" @yield('date-value') value="{{ old('txtDate') }}" @yield('read-only') placeholder="Recieve Date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 mb-3 mb-sm-0">
                    <select name="cbocategories" id="cbocategories" @yield('read-only') class="form-control form-control-user-1">
                        @yield('categories')
                    </select>
                </div>
                <div class="col-sm-1">
                    <!-- <input type="text" class="form-control form-control-user" id="exampleRepeatPassword" disabled style="font-size:x-large;" placeholder="&#43;"> -->

                    <!-- Button trigger modal -->
                    <button type="button" class="form-control form-control-user d-flex align-items-center justify-content-center" data-toggle="modal"  @yield('read-only') data-target="#saveCategory">
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
                <textarea name="txtDescription" id="exampleInputEmail" class="form-control form-control-user" cols="30" rows="2" @yield('read-only') placeholder="Description">@yield('description-value'){{ old('txtDescription') }}</textarea>
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

@yield('form-scripts')

@endsection