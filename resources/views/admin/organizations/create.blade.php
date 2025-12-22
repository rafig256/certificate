@extends('adminlte::page')

@section('title', 'افزودن سازمان جدید')

@section('content_header')
    <h1>افزودن سازمان جدید</h1>
    <a href="{{ route('organizations.index') }}" class="btn btn-secondary">بازگشت به لیست</a>
@stop

@section('content')
    <form action="{{ route('organizations.store') }}" method="POST">
        @include('admin.organizations.form')
    </form>
@stop
