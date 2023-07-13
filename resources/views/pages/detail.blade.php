@php
$currentUrl = url()->current();
$previousUrl = url()->previous();
if ($currentUrl != $previousUrl)
Session::put('requestReferrer', $previousUrl);
@endphp

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
@lang('public.itemList')
@endsection

@section('body-container')
<!-- Begin Page Content -->
<div class="container-fluid">
    @if($errors->any())
    <div class="card mb-4 py-3 border-bottom-dander alert alert-light alert-dismissible fade show" role="alert">
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


    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <a href="{{ Session::get('requestReferrer') }}" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">@lang('public.back')</span>
            </a>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle text-dark" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-900"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" style="">
                    <div class="dropdown-header">Actions :</div>
                    @if ($item['deleted_at'] == null)
                    <a class="dropdown-item" href="{{ route('items.update', ['id' => $item['id']] ) }}">@lang('public.update')</a>
                    @else
                    <div data-toggle="tooltip" title="Feature Disabled for Inactive Items!">
                        <a class="dropdown-item disabled" disabled href="#" data-toggle="tooltip">@lang('public.update')</a>
                    </div>
                    @endif

                    <div class="dropdown-divider"></div>
                    <div class="dropdown-header">Download Item via :</div>

                    <form action="{{ route('items.export', ['id' => $item['id'] ]) }}" method="get">
                        @csrf
                        <input type="hidden" value="excel" name="type">
                        <button type="submit" class="dropdown-item">Excel File</button>
                    </form>
                    <form action="{{ route('items.export', ['id' => $item['id'] ]) }}" method="get">
                        @csrf
                        <input type="hidden" value="pdf" name="type">
                        <button type="submit" class="dropdown-item">PDF File</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="row text-left">
                <ul class="list-group list-group-flush col-5">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">@lang('public.itemId')</div>
                            <div class="col-6">{{ $item['item_id'] }}</div>
                        </div>

                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">@lang('public.itemName')</div>
                            <div class="col-6">{{ $item['item_name'] }}</div>
                        </div>

                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">@lang('public.itemCode')</div>
                            <div class="col-6">{{ $item['item_code'] }}</div>
                        </div>

                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">@lang('public.category')</div>
                            <div class="col-6">{{ $item['name'] }}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">@lang('public.safetyStock')</div>
                            <div class="col-6">{{ $item['safety_stock'] }}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">@lang('public.receivedDate')</div>
                            <div class="col-6">{{ $item['received_date'] }}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">@lang('public.status')</div>
                            <div class="col-6">
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
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="col-7" style="max-height: 24rem;">
                    <a href="#">
                        <img class="card-img-top rounded" style="max-height: 24rem;" src="{{ $item['image'] ? asset($item['image']) : asset('placeholder-image/photos-animate.svg') }}" alt="Card image cap">
                    </a>
                </div>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-3">@lang('public.description')</div>
                        <div class="col-9">{{ $item['description'] }}</div>
                    </div>

                </li>
            </ul>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="modelActiveChange" data-dismiss="modal" onclick="location.href='{{ route('items.active', ['id' => ':itemId']) }}'.replace(':itemId', $('#activeModalItemId').text())">Active</button>
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
            // Set the values in the modal
            $('#inactiveModalItemId').text(itemId);
        });

        $('#itemActiveModel').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var itemId = button.data('id'); // Extract item ID from data-id attribute


            // Set the values in the modal
            $('#activeModalItemId').text(itemId);
        });
    });
</script>

@endsection