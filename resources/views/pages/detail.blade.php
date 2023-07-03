@extends('layouts.form')

@section('form-nav-title')
Item Details
@endsection


@section('form-nav-item-link')
{{ route('items.list') }}
@endsection


@section('form-nav-item-text')
Back
@endsection

@section('item-id')
    {{ $item['item_id'] }}
@endsection

@section('code-value')
    value = "{{ $item['item_code'] }}"
@endsection

@section('categories')
<option disabled selected hidden value="">{{ $item['name']  }}</option>
@endsection

@section('name-value')
    value = "{{ $item['item_name'] }}"
@endsection

@section('stock-value')
    value = "{{ $item['safety_stock']  }}"
@endsection

@section('date-value')
    value = "{{ $item['received_date'] }}"
@endsection

@section('description-value')
    {{ $item['description']  }}
@endsection




@section('read-only')
disabled
@endsection     