@extends('layouts.app')

@section('title')
@lang('public.item')
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
@lang('public.list')
@endsection

@section('download')
<a class="nav-link dropdown-toggle  text-dark" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    @lang('public.downloadAllItem')
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
@lang('public.addNewItem')
@endsection

@section('body-container')
<!-- Begin Page Content -->
<div class="container-fluid">

    @if($errors->any())
    <div class="card mb-4 py-3 border-bottom-danger alert alert-light alert-dismissible fade show" role="alert">
        <strong>@lang('public.error')!</strong>
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
        <strong>@lang('public.success')!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="p-1 mb-2">
        <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">@lang('public.searchItem')</h1>
        </div>
        <form class="user" action="{{ route('items.search') }}" method="get" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
                <div class="col-sm-2 mb-3 mb-sm-0">
                    <div class="form-floating">
                        <input type="text" name="txtItemId" list="item_id_suggests" class="form-control form-control-user" id="item_id" placeholder="Item ID" value="{{ $formData['txtItemId'] ?? '' }}" autocomplete="off">
                        <label for="item_id">@lang('public.itemId')</label>
                        <datalist id="item_id_suggests">
                        </datalist>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-floating">
                        <input type="text" name="txtCode" class="form-control form-control-user" id="item_code" value="{{ $formData['txtCode'] ?? '' }}" placeholder="Item Code">
                        <label for="item_code">@lang('public.itemCode')</label>
                    </div>
                </div>
                <div class="col-sm-3 mb-3 mb-sm-0">
                    <div class="form-floating">
                        <input type="text" name="txtItemName" class="form-control form-control-user" id="item_name" value="{{ $formData['txtItemName'] ?? '' }}" placeholder="Item Name">
                        <label for="item_name">@lang('public.itemName')</label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-floating">
                        <select name="cboCategories" id="cboCategories" class="form-control form-control-user-1">
                            <option value="" disabled selected hidden>@lang('public.choose')</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ isset($formData['cboCategories']) && $formData['cboCategories'] == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <label for="cboCategories">@lang('public.category')</label>
                    </div>
                </div>
                <div class="col-sm-2">
                    <input type="submit" class="btn btn-primary btn-user btn-block" name="btnSearch" value="@lang('public.search')">
                </div>
            </div>
        </form>

    </div>




    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">@lang('public.item')</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>@lang('public.itemCode')</th>
                            <th>@lang('public.itemName')</th>
                            <th>@lang('public.category')</th>
                            <th>@lang('public.safetyStock')</th>
                            <th>@lang('public.status')</th>
                            <th>@lang('public.actions')</th>
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
                                    <div data-toggle="tooltip" title="@lang('public.tapToInactive')">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="text">
                                            @lang('public.active')
                                        </span>
                                    </div>

                                </button>
                                @else
                                <!-- Button trigger ActiveModel    -->
                                <button type="button" class="btn btn-secondary btn-icon-split" data-id="{{ $item['id'] }}" data-toggle="modal" data-toggle="tooltip" data-target="#itemActiveModel" title="Active">
                                    <div data-toggle="tooltip" title="@lang('public.tapToActive')">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-ban"></i>
                                        </span>
                                        <span class="text">
                                            @lang('public.inactive')
                                        </span>
                                    </div>

                                </button>
                                @endif


                            </td>
                            <td>
                                <a href="{{ route('items.detail', ['id' => $item['id']]) }}" class="btn btn-info btn-icon-split tooltip-test" data-toggle="tooltip" data-placement="top" title="@lang('public.detail')">
                                    <span class="text">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </a>
                                @if ($item['deleted_at'] == null)
                                <a href="{{ route('items.update', ['id' => $item['id']]) }}" class="btn btn-warning btn-icon-split tooltip-test" data-toggle="tooltip" data-placement="top" title="@lang('public.update')">
                                    <span class="text">
                                        <i class="fas fa-wrench"></i>
                                    </span>
                                </a>
                                <button class="btn btn-danger btn-icon-split tooltip-test" data-toggle="modal" data-target="#itemDeleteModel" data-id="{{ $item['id'] }}">
                                    <span class="text" data-toggle="tooltip" title="@lang('public.delete')" data-placement="top">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </button>
                                @else
                                <button class="btn btn-warning btn-icon-split tooltip-test" data-toggle="tooltip" data-placement="top" title="@lang('public.featureDisable')!">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-wrench"></i>
                                    </span>
                                </button>
                                <button class="btn btn-danger btn-icon-split tooltip-test" data-toggle="tooltip" data-placement="top" title="@lang('public.featureDisable')!">
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
                                            @lang('public.downloadSearch')
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
                    <td colspan="4">@lang('public.noData')</td>
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
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('public.sureInactive')?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>@lang('public.itemId'): <span id="inactiveModalItemId"></span></p>
                <p>@lang('public.itemCode'): <span id="inactiveModalItemCode"></span></p>
                <p>@lang('public.itemName'): <span id="inactiveModalItemName"></span></p>
                <p>@lang('public.category'): <span id="inactiveModalCategoryName"></span></p>
                <p>@lang('public.safetyStock'): <span id="inactiveModalSafetyStock"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('public.close')</button>
                <button type="button" class="btn btn-primary" id="modelInactiveChange" data-dismiss="modal" onclick="location.href='{{ route('items.inactive', ['id' => ':itemId']) }}'.replace(':itemId', $('#inactiveModalItemId').text())">@lang('public.tapToInactive')</button>
            </div>
        </div>
    </div>
</div>

<!-- Active Modal -->
<div class="modal fade" id="itemActiveModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('public.sureActive')?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>@lang('public.itemId'): <span id="activeModalItemId"></span></p>
                <p>@lang('public.itemCode'): <span id="activeModalItemCode"></span></p>
                <p>@lang('public.itemName'): <span id="activeModalItemName"></span></p>
                <p>@lang('public.category'): <span id="activeModalCategoryName"></span></p>
                <p>@lang('public.safetyStock'): <span id="activeModalSafetyStock"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('public.close')</button>
                <button type="button" class="btn btn-primary" id="modelActiveChange" data-dismiss="modal" onclick="location.href='{{ route('items.active', ['id' => ':itemId']) }}'.replace(':itemId', $('#activeModalItemId').text())">@lang('public.tapToActive')</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="itemDeleteModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('public.sureDelete')?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>@lang('public.itemId'): <span id="deleteModalItemId"></span></p>
                <p>@lang('public.itemCode'): <span id="deleteModalItemCode"></span></p>
                <p>@lang('public.itemName'): <span id="deleteModalItemName"></span></p>
                <p>@lang('public.category'): <span id="deleteModalCategoryName"></span></p>
                <p>@lang('public.safetyStock'): <span id="deleteModalSafetyStock"></span></p>
            </div>
            <div class="modal-footer bg-light shadow-sm">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('public.close')</button>
                <form action="{{ route('items.delete') }}" method="POST">
                    @csrf @method('DELETE')
                    <input type="hidden" value="" name="txtId" id="deleteID">
                    <button type="submit" class="btn btn-danger" id="modelDeleteChange">@lang('public.delete')</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {

        $.ajax({
            url: "{{ route('items.suggestions') }}",
            type: 'GET',
            success: function(data) {
                var datalist = $('#item_id_suggests');
                $.each(data, function(index, item) {
                    datalist.append('<option value="' + item + '">');
                });
            }
        });

        // Get the input elements
        var txtItemId = $('#item_id');
        var txtCode = $('#item_code');
        var txtItemName = $('#item_name');

        // Handle change event on txtItemId
        txtItemId.on('input', function() {
            var selectedItem = $(this).val();
            // Check if the selected item is in the datalist
            var option = $('datalist#item_id_suggests').find('option[value="' + selectedItem + '"]');
            if (option.length > 0) {
                // Fetch data from the database using AJAX
                $.ajax({
                    url: "{{ route('items.fetch-details') }}",
                    type: 'GET',
                    data: {
                        item_id: selectedItem
                    },
                    success: function(data) {
                        // Update txtCode and txtItemName with the fetched data
                        txtCode.val(data.code);
                        txtItemName.val(data.name);
                    }
                });
            }
        });


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