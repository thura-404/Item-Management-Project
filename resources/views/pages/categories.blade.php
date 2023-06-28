@extends('layouts.app')

@section('body-container')
    @foreach ($deletableCategories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
@endsection