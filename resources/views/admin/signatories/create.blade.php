@extends('adminlte::page')

@section('title', 'ایجاد امضا کننده')

@section('content_header')
    <h1>ایجاد امضا کننده جدید</h1>
@stop

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('signatories.store') }}" method="POST">
        @include('admin.signatories.form', ['buttonText' => 'ثبت'])
    </form>
@stop
