@extends('adminlte::page')

@section('title', 'سازمان‌ها')

@section('content_header')
    <h1>سازمان‌ها</h1>
    <a href="{{ route('organizations.create') }}" class="btn btn-primary">افزودن سازمان جدید</a>
@stop

@section('content')
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>نام</th>
            <th>ایمیل</th>
            <th>تلفن</th>
            <th>وبسایت</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @foreach($organizations as $org)
            <tr>
                <td>{{ $org->name }}</td>
                <td>{{ $org->email }}</td>
                <td>{{ $org->phone }}</td>
                <td>{{ $org->website }}</td>
                <td>
                    <a href="{{ route('organizations.edit', $org) }}" class="btn btn-sm btn-warning">ویرایش</a>
                    <form action="{{ route('organizations.destroy', $org) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('آیا مطمئن هستید؟')">حذف</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
