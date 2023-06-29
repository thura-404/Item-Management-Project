@extends('layouts.form')

@section('form-nav-title')
Item Details
@endsection


@section('form-nav-item-link')
{{ route('items.register-form') }}
@endsection


@section('form-nav-item-text')
New Item
@endsection
