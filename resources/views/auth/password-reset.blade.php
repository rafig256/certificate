<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بازیابی رمز عبور</title>
    <link rel="stylesheet" href="{{asset('css/reset_password.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom-fonts.css')}}">
</head>
<body>

<div class="card">
    <div class="logo">{{config('settings.app_name')}}</div>

    <h1>بازیابی رمز عبور</h1>
    <p>
    کد ملی خود را ثبت کنید تا لینک بازیابی به ایمیل شما ارسال شود. اگر ایمیلی در پنل خود ثبت نکرده باشید با مدیریت سایت برای بازیابی کلمه ی عبور تماس بگیرید.
    </p>
    @if (session('status'))
        <div class="alert success">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{route('password.email')}}">
        @csrf
        <div class="form-group">
            <label for="identity">کد ملی خود را ثبت کنید</label>
            <input
                type="text"
                id="identity"
                name="national_code"
                required
            >
        </div>
        @error('national_code')
            <div class="alert error">
                {{ $message }}
            </div>
        @enderror

        <button type="submit">ارسال لینک بازیابی</button>
    </form>

    <div class="footer-link">
        <a href="admin/login">بازگشت به صفحه ورود</a>
    </div>
</div>

</body>
</html>
