<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استعلام گواهینامه</title>
    <link rel="stylesheet" href="{{asset('css/reset_password.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom-fonts.css')}}">
</head>
<body>

<div class="card">
    <div class="logo">{{config('settings.app_name')}}</div>

    <h1>استعلام گواهینامه</h1>
    <p>
        کد رهگیری گواهینامه را وارد کنید تا اعتبار آن بررسی شود.
    </p>

    <form method="POST" action="{{route('certificate.verify')}}">
        @csrf

        <div class="form-group">
            <label for="code">کد رهگیری</label>
            <input
                type="text"
                id="code"
                name="code"
                required
                value="{{ old('code') }}"
            >
        </div>

        @error('code')
        <div class="alert error">
            {{ $message }}
        </div>
        @enderror

        <button type="submit">استعلام</button>
    </form>

    <div class="footer-link">
        <a href="/">بازگشت به صفحه اصلی</a>
    </div>
</div>

</body>
</html>
