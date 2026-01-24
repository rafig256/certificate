{{-- resources/views/auth/password-reset.blade.php --}}

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <label>کد ملی</label>
    <input type="text" name="national_code">

    @error('national_code')
    <div>{{ $message }}</div>
    @enderror

    <button type="submit">ارسال لینک بازیابی</button>

    @if(session('status'))
        <div>{{ session('status') }}</div>
    @endif
</form>
