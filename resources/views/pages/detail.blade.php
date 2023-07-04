@extends('layouts.app')

@section('title')
Items
@endsection


@section('body-id')
page-top
@endsection

@section('nav-title')
Item Details
@endsection

@section('nav-item-link')
{{ route('items.list') }}
@endsection

@section('nav-item-text')
Back
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


    <div class="card mb-3">
        @if (!$item['image'] == null)
        <div class="shadow-sm rounded" style="width: 98%; max-height: auto; margin-left: 1%; margin-top: -1%; border-radius: .5rem;">
            <img class="card-img-top rounded" src="{{ $item['image'] ? asset($item['image']) : '' }}" alt="Card image cap">
        </div>
        @endif

        <div class="card-body">
            <div class="row mb-1">
                <div class="col-6">
                    <h5 class="text-dark">Item ID</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-title">{{ $item['item_id'] }}</h6>
                </div>
            </div>
            <hr>
            <div class="row mb-1">
                <div class="col-6">
                    <h5 class="text-dark">Item Name</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-title">{{ $item['item_name'] }}</h6>
                </div>
            </div>
            <hr>
            <div class="row mb-1">
                <div class="col-6">
                    <h5 class="text-dark">Item Code</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-title">{{ $item['item_code'] }}</h6>
                </div>
            </div>
            <hr>
            <div class="row mb-1">
                <div class="col-6">
                    <h5 class="text-dark">Category Name</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-title">{{ $item['name'] }}</h6>
                </div>
            </div>
            <hr>
            <div class="row mb-1">
                <div class="col-6">
                    <h5 class="text-dark">Safety Stock</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-title">{{ $item['safety_stock'] }}</h6>
                </div>
            </div>
            <hr>
            <div class="row mb-1">
                <div class="col-6">
                    <h5 class="text-dark">Received Date</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-title">{{ $item['received_date'] }}</h6>
                </div>
            </div>
            <hr>
            <div class="row mb-1">
                <div class="col-6">
                    <h5 class="text-dark">Status</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-title">@if ($item['deleted_at'] == null)
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
                    </h6>
                </div>
            </div>
            <hr>
            <div class="row mb-1">
                <div class="col-6">
                    <h5 class="text-dark">Description</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-title">{{ $item['description'] }}</h6>
                </div>
            </div>
            <hr>
            <div class="row mb-1">
                <div class="col-6">
                    <h5 class="text-dark">Item Name</h5>
                </div>
                <div class="col-6">
                    <h6 class="card-title">{{ $item['item_name'] }}</h6>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->


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