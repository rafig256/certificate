@extends('adminlte::page')

@section('title', 'ویرایش امضا کننده')

@section('content_header')
    <h1>ویرایش امضا کننده</h1>
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

    <form action="{{ route('signatories.update', $signatory) }}" method="POST">
        @method('PUT')
        @include('admin.signatories.form', ['buttonText' => 'بروزرسانی'])
    </form>
@stop
