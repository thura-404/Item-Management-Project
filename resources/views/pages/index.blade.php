@extends('layouts.app')

@section('title')
Items
@endsection

@section('cs-style')
<style>
    .form-control-user-1 {
        font-size: 0.8rem;
        border-radius: 10rem;
        height: 3rem;
    }
</style>
@endsection


@section('body-id')
page-top
@endsection

@section('nav-title')
List
@endsection

@section('nav-item-link')
{{ route('items.register-form') }}
@endsection

@section('nav-item-text')
New Item
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

    <div class="p-1 mb-2">
        <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Search Items</h1>
        </div>
        <form class="user" action="{{ route('items.search') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
                <div class="col-sm-2 mb-3 mb-sm-0">
                    <input type="text" name="txtItemID" class="form-control form-control-user" id="exampleFirstName" placeholder="Item ID">
                </div>
                <div class="col-sm-2">
                    <input type="text" name="txtCode" class="form-control form-control-user" id="exampleLastName" placeholder="Item Code">
                </div>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="text" name="txtItemName" class="form-control form-control-user" id="exampleInputPassword" placeholder="Item Name">
                </div>
                <div class="col-sm-3">
                    <select name="cboCategories" id="cboCategories" class="form-control form-control-user-1">
                        <option value="" disabled selected hidden>Choose</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-sm-2">
                    <input type="submit" class="btn btn-primary btn-user btn-block" name="btnSearch" value="Search">
                </div>

            </div>

        </form>
    </div>




    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Items</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Category Name</th>
                            <th>Safety Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($items) > 0)
                        @foreach ($items as $item)
                        <tr>
                            <td>{{ $item['item_code'] }}</td>
                            <td>{{ $item['item_name'] }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['safety_stock'] }}</td>
                            <td>
                                @if ($item['deleted_at'] == null)
                                <a href="{{ route('items.inactive', ['id' => $item['id']]) }}" class="btn btn-success btn-icon-split" title="Inactive">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">
                                        Active
                                    </span>
                                </a>
                                @else
                                <a href="" class="btn btn-secondary btn-icon-split" title="Active">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-ban"></i>
                                    </span>
                                    <span class="text">
                                        Inactive
                                    </span>
                                </a>
                                @endif


                            </td>
                            <td>
                                <a href="" class="btn btn-info btn-icon-split" title="Details">
                                    <span class="text">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </a>
                                <a href="" class="btn btn-warning btn-icon-split" title="Update">
                                    <span class="text">
                                        <i class="fas fa-wrench"></i>
                                    </span>
                                </a>
                                <a href="" class="btn btn-danger btn-icon-split" title="Delete">
                                    <span class="text">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4">No data available</td>
                        </tr>
                        @endif

                    </tbody>
                </table>
                {{ $items->links() }}
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->



<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

@endsection