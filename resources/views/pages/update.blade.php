@php
$currentUrl = url()->current();
$previousUrl = url()->previous();
if ($currentUrl != $previousUrl)
Session::put('requestReferrer', $previousUrl);
@endphp

@extends('layouts.form')

@section('form-nav-title')
Item Update
@endsection


@section('form-nav-item-link')
{{ route('items.list') }}
@endsection

@section('form-action')
{{ route('items.update-data', ['id' => $item['id']]) }}
@endsection

@section('form-method')
@method('PATCH')
@endsection


@section('form-nav-item-text')
@lang('public.itemList')
@endsection

@section('image-display-block')
style="display: block"
@endsection

@section('image-display-none')
style="display: none"
@endsection

@section('image-value')
src="{{ $item['image'] ? asset($item['image']) : '' }}"
@endsection

@section('item-id')
{{ $item['item_id'] }}
@endsection

@section('code-value')
value = "{{ $item['item_code'] }}"
@endsection

@section('categories')
@foreach ($categories as $category)
<option value="{{ $category->id }}" {{ $item['name'] == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
@endforeach
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