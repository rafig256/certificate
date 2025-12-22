@extends('adminlte::page')

@section('title', 'ویرایش سازمان')

@section('content_header')
    <h1>ویرایش سازمان</h1>
    <a href="{{ route('organizations.index') }}" class="btn btn-secondary">بازگشت به لیست</a>
@stop

@section('content')
    <form action="{{ route('organizations.update', $organization) }}" method="POST">
        @include('admin.organizations.form')
    </form>
@stop
