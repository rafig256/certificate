@extends('adminlte::page')

@section('title', 'جزئیات امضا کننده')

@section('content_header')
    <h1>جزئیات امضا کننده</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>نام:</strong> {{ $signatory->name }}</p>
            <p><strong>ایمیل:</strong> {{ $signatory->email }}</p>
            <p><strong>تلفن:</strong> {{ $signatory->phone }}</p>
            <p><strong>نوع:</strong> {{ $signatory->type }}</p>
            <p><strong>سطح:</strong> {{ $signatory->level }}</p>
            <p><strong>تاریخ ایجاد:</strong> {{ $signatory->created_at->format('Y-m-d') }}</p>
        </div>
    </div>

    <a href="{{ route('signatories.index') }}" class="btn btn-secondary mt-3">بازگشت</a>
@stop
