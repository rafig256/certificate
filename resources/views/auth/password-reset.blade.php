<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بازیابی رمز عبور</title>

    <style>
        :root {
            --blue-600: #2563eb;
            --blue-500: #3b82f6;
            --blue-100: #dbeafe;
            --gray-700: #374151;
            --gray-500: #6b7280;
            --gray-100: #f3f4f6;
        }

        * {
            box-sizing: border-box;
            font-family: Vazirmatn, system-ui, -apple-system, BlinkMacSystemFont;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--blue-100), #ffffff);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,.08);
            padding: 32px;
        }

        .logo {
            text-align: center;
            margin-bottom: 24px;
            font-size: 22px;
            font-weight: 700;
            color: var(--blue-600);
        }

        h1 {
            font-size: 18px;
            margin: 0 0 8px;
            color: var(--gray-700);
            text-align: center;
        }

        p {
            font-size: 14px;
            color: var(--gray-500);
            text-align: center;
            margin-bottom: 24px;
            line-height: 1.7;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: var(--gray-700);
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: var(--blue-500);
            box-shadow: 0 0 0 3px rgba(59,130,246,.2);
        }

        button {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            background: var(--blue-600);
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s ease;
        }

        button:hover {
            background: var(--blue-500);
        }

        .footer-link {
            margin-top: 16px;
            text-align: center;
            font-size: 13px;
        }

        .footer-link a {
            color: var(--blue-600);
            text-decoration: none;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="logo">{{config('settings.app_name')}}</div>

    <h1>بازیابی رمز عبور</h1>
    <p>
    کد ملی خود را ثبت کنید تا لینک بازیابی به ایمیل شما ارسال شود. اگر ایمیلی در پنل خود ثبت نکرده باشید با مدیریت سایت برای بازیابی کلمه ی عبور تماس بگیرید.
    </p>

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

        <button type="submit">ارسال لینک بازیابی</button>
    </form>

    <div class="footer-link">
        <a href="admin/login">بازگشت به صفحه ورود</a>
    </div>
</div>

</body>
</html>
