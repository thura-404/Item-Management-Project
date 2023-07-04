@extends('layouts.form')

@section('form-nav-title')
Item Register
@endsection


@section('form-nav-item-link')
{{ route('items.excel-form') }}
@endsection

@section('form-action')
{{ route('items.register') }}
@endsection


@section('form-nav-item-text')
Add Excel File
@endsection

@section('form-switch')
<div class="btn-group btn-group-toggle" data-toggle="buttons">
    <a href="{{ route('items.register-form') }}" class="btn btn-secondary active">
        <input type="radio" name="options" id="option1" autocomplete="off" checked> Register Manually
    </a>
    <a href="{{ route('items.excel-form') }}" class="btn btn-secondary">
        <input type="radio" name="options" id="option2" autocomplete="off"> Add Excel File
    </a>
</div>
@endsection

@section('item-id')
{{ $itemId }}
@endsection

@section('categories')
<option disabled selected hidden value="">Select Category</option>
@foreach ($categories as $category)
<option value="{{ $category->id }}" {{ old('cboCategories') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
@endforeach
@endsection

