@extends('adminlte::page')

@section('title', 'لیست امضا کنندگان')

@section('content_header')
    <h1>لیست امضا کنندگان</h1>
    <a href="{{ route('signatories.create') }}" class="btn btn-success">امضا کننده جدید</a>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($signatories->count())
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>نام</th>
                        <th>ایمیل</th>
                        <th>تلفن</th>
                        <th>نوع</th>
                        <th>سطح</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($signatories as $signatory)
                        <tr>
                            <td>{{ $signatory->name }}</td>
                            <td>{{ $signatory->email }}</td>
                            <td>{{ $signatory->phone }}</td>
                            <td>{{ $signatory->type }}</td>
                            <td>{{ $signatory->level }}</td>
                            <td>
                                <a href="{{ route('signatories.show', $signatory) }}" class="btn btn-info btn-sm">مشاهده</a>
                                <a href="{{ route('signatories.edit', $signatory) }}" class="btn btn-warning btn-sm">ویرایش</a>
                                <form action="{{ route('signatories.destroy', $signatory) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('آیا مطمئن هستید حذف شود؟')">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $signatories->links() }}
            @else
                <p>هیچ امضا کننده‌ای ثبت نشده است.</p>
            @endif
        </div>
    </div>
@stop
