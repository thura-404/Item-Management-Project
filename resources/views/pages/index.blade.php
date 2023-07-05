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

@section('download')
<a class="nav-link dropdown-toggle  text-dark" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Download All Items
</a>
<div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="navbarDropdown">
    <a class="dropdown-item" href="{{ route('items.export-all', ['type' => 'excel']) }}">Dowanload Excel</a>
    <a class="dropdown-item" href="{{ route('items.export-all', ['type' => 'pdf']) }}">Download PDF</a>
</div>

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
    <div class="card mb-4 py-3 border-bottom-danger alert alert-light alert-dismissible fade show" role="alert">
        <strong>Error!</strong>
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
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="p-1 mb-2">
        <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Search Items</h1>
        </div>
        <form class="user" action="{{ route('items.search') }}" method="get" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
                <div class="col-sm-2 mb-3 mb-sm-0">
                    <input type="text" name="txtItemId" class="form-control form-control-user" id="item_id" placeholder="Item ID" value="{{ $formData['txtItemId'] ?? '' }}">
                </div>
                <div class="col-sm-2">
                    <input type="text" name="txtCode" class="form-control form-control-user" id="item_code" value="{{ $formData['txtCode'] ?? '' }}" placeholder="Item Code">
                </div>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <input type="text" name="txtItemName" class="form-control form-control-user" id="item_name" value="{{ $formData['txtItemName'] ?? '' }}" placeholder="Item Name">
                </div>
                <div class="col-sm-3">
                    <select name="cboCategories" id="cboCategories" class="form-control form-control-user-1">
                        <option value="" disabled selected hidden>Choose</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ isset($formData['cboCategories']) && $formData['cboCategories'] == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                <!-- Button trigger InactiveModel  -->
                                <button type="button" class="btn btn-success btn-icon-split" data-id="{{ $item['id'] }}" data-toggle="modal" data-target="#itemInactiveModel">
                                    <div data-toggle="tooltip" title="Tap to Inactive">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="text">
                                            Active
                                        </span>
                                    </div>

                                </button>
                                @else
                                <!-- Button trigger ActiveModel    -->
                                <button type="button" class="btn btn-secondary btn-icon-split" data-id="{{ $item['id'] }}" data-toggle="modal" data-toggle="tooltip" data-target="#itemActiveModel" title="Active">
                                    <div data-toggle="tooltip" title="Tap to Active">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-ban"></i>
                                        </span>
                                        <span class="text">
                                            Inactive
                                        </span>
                                    </div>

                                </button>
                                @endif


                            </td>
                            <td>
                                <a href="{{ route('items.detail', ['id' => $item['id']]) }}" class="btn btn-info btn-icon-split tooltip-test" data-toggle="tooltip" data-placement="top" title="Details">
                                    <span class="text">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </a>
                                @if ($item['deleted_at'] == null)
                                <a href="{{ route('items.update', ['id' => $item['id']]) }}" class="btn btn-warning btn-icon-split tooltip-test" data-toggle="tooltip" data-placement="top" title="Update">
                                    <span class="text">
                                        <i class="fas fa-wrench"></i>
                                    </span>
                                </a>
                                <button class="btn btn-danger btn-icon-split tooltip-test" data-toggle="modal" data-target="#itemDeleteModel" data-id="{{ $item['id'] }}">
                                    <span class="text" data-toggle="tooltip" title="Delete" data-placement="top">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </button>
                                @else
                                <button class="btn btn-warning btn-icon-split tooltip-test" data-toggle="tooltip" data-placement="top" title="Feature Disabled for Inactive items!">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-wrench"></i>
                                    </span>
                                </button>
                                <button class="btn btn-danger btn-icon-split tooltip-test" data-toggle="tooltip" data-placement="top" title="Feature Disabled for Inactive items!">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </button>
                                @endif

                            </td>
                        </tr>
                        @endforeach

                        @if (isset($search))
                        @if ($search)
                    <tfoot>
                        <tr>
                            <th colspan="6" class="justify-content-end">
                                <div class="w-100 d-flex justify-content-end">
                                    <div class="dropdown no-arrow mb-4">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Download Search Result
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <form action="{{ route('items.search-export') }}" method="get">
                                                @csrf
                                                <input type="hidden" value="{{ $formData['txtItemId'] ?? '' }}" name="txtItemId">
                                                <input type="hidden" value="{{ $formData['txtCode'] ?? '' }}" name="txtCode">
                                                <input type="hidden" value="{{ $formData['txtName'] ?? '' }}" name="txtItemName">
                                                <input type="hidden" value="{{ $formData['cboCategories'] ?? '' }}" name="cboCategories">
                                                <input type="hidden" value="excel" name="type">
                                                <button type="submit" class="dropdown-item">Download Excel</button>
                                            </form>
                                            <form action="{{ route('items.search-export') }}" method="get">
                                                @csrf
                                                <input type="hidden" value="{{ $formData['txtItemId'] ?? '' }}" name="txtItemId">
                                                <input type="hidden" value="{{ $formData['txtCode'] ?? '' }}" name="txtCode">
                                                <input type="hidden" value="{{ $formData['txtName'] ?? '' }}" name="txtItemName">
                                                <input type="hidden" value="{{ $formData['cboCategories'] ?? '' }}" name="cboCategories">
                                                <input type="hidden" value="pdf" name="type">
                                                <button type="submit" class="dropdown-item">Download PDF</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                            </th>
                        </tr>
                    </tfoot>
                    @endif
                    @endif
                    </tbody>
                </table>
                @else
                <tr>
                    <td colspan="4">No data available</td>
                </tr>
                </tbody>
                </table>
                @endif


                <!-- Display pagination links -->
                {{ $items->appends(['txtItemId' => request('txtItemId'), 'txtCode' => request('txtCode'),  'txtName' => request('txtName'), 'cboCategories' => request('cboCategories')])->links() }}
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Inactive Modal -->
<div class="modal fade" id="itemInactiveModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you Sure you want to Inactive Item?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Item ID: <span id="inactiveModalItemId"></span></p>
                <p>Item Code: <span id="inactiveModalItemCode"></span></p>
                <p>Item Name: <span id="inactiveModalItemName"></span></p>
                <p>Category Name: <span id="inactiveModalCategoryName"></span></p>
                <p>Safety Stock: <span id="inactiveModalSafetyStock"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="modelInactiveChange" data-dismiss="modal" onclick="location.href='{{ route('items.inactive', ['id' => ':itemId']) }}'.replace(':itemId', $('#inactiveModalItemId').text())">Inactive</button>
            </div>
        </div>
    </div>
</div>

<!-- Active Modal -->
<div class="modal fade" id="itemActiveModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you Sure you want to Active Item?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Item ID: <span id="activeModalItemId"></span></p>
                <p>Item Code: <span id="activeModalItemCode"></span></p>
                <p>Item Name: <span id="activeModalItemName"></span></p>
                <p>Category Name: <span id="activeModalCategoryName"></span></p>
                <p>Safety Stock: <span id="activeModalSafetyStock"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="modelActiveChange" data-dismiss="modal" onclick="location.href='{{ route('items.active', ['id' => ':itemId']) }}'.replace(':itemId', $('#activeModalItemId').text())">Active</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="itemDeleteModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you Sure you want to Delete Item?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Item ID: <span id="deleteModalItemId"></span></p>
                <p>Item Code: <span id="deleteModalItemCode"></span></p>
                <p>Item Name: <span id="deleteModalItemName"></span></p>
                <p>Category Name: <span id="deleteModalCategoryName"></span></p>
                <p>Safety Stock: <span id="deleteModalSafetyStock"></span></p>
            </div>
            <div class="modal-footer bg-light shadow-sm">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <form action="{{ route('items.delete') }}" method="POST">
                    @csrf @method('DELETE')
                    <input type="hidden" value="" name="txtId" id="deleteID">
                    <button type="submit" class="btn btn-danger" id="modelDeleteChange">Delete</button>
                </form>
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
    $(document).ready(function() {

        $('[data-toggle="tooltip"]').tooltip()

        $('#itemInactiveModel').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var itemId = button.data('id'); // Extract item ID from data-id attribute
            var itemCode = button.closest('tr').find('td:eq(0)').text(); // Get item code from table row
            var itemName = button.closest('tr').find('td:eq(1)').text(); // Get item name from table row
            var categoryName = button.closest('tr').find('td:eq(2)').text(); // Get category name from table row
            var safetyStock = button.closest('tr').find('td:eq(3)').text(); // Get safety stock from table row

            // Set the values in the modal
            $('#inactiveModalItemId').text(itemId);
            $('#inactiveModalItemCode').text(itemCode);
            $('#inactiveModalItemName').text(itemName);
            $('#inactiveModalCategoryName').text(categoryName);
            $('#inactiveModalSafetyStock').text(safetyStock);
        });

        $('#itemActiveModel').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var itemId = button.data('id'); // Extract item ID from data-id attribute
            console.log(itemId);
            var itemCode = button.closest('tr').find('td:eq(0)').text(); // Get item code from table row
            var itemName = button.closest('tr').find('td:eq(1)').text(); // Get item name from table row
            var categoryName = button.closest('tr').find('td:eq(2)').text(); // Get category name from table row
            var safetyStock = button.closest('tr').find('td:eq(3)').text(); // Get safety stock from table row

            // Set the values in the modal
            $('#activeModalItemId').text(itemId);
            $('#activeModalItemCode').text(itemCode);
            $('#activeModalItemName').text(itemName);
            $('#activeModalCategoryName').text(categoryName);
            $('#activeModalSafetyStock').text(safetyStock);
        });

        $('#itemDeleteModel').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var itemId = button.data('id'); // Extract item ID from data-id attribute
            console.log(itemId);
            var itemCode = button.closest('tr').find('td:eq(0)').text(); // Get item code from table row
            var itemName = button.closest('tr').find('td:eq(1)').text(); // Get item name from table row
            var categoryName = button.closest('tr').find('td:eq(2)').text(); // Get category name from table row
            var safetyStock = button.closest('tr').find('td:eq(3)').text(); // Get safety stock from table row

            // Set the values in the modal
            $('#deleteModalItemId').text(itemId);
            $('#deleteID').val(itemId);
            $('#deleteModalItemCode').text(itemCode);
            $('#deleteModalItemName').text(itemName);
            $('#deleteModalCategoryName').text(categoryName);
            $('#deleteModalSafetyStock').text(safetyStock);
        });
    });
</script>

@endsection