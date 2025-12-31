<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>گواهینامه {{ $certificate->serial }}</title>
    <link rel="stylesheet" href="{{ asset('css/certificate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-fonts.css') }}">
    @if($certificate->event->template && $certificate->event->template->css_file_path)
        <link rel="stylesheet" href="{{ asset('storage/' . $certificate->event->template->css_file_path) }}">
    @endif
</head>
<body>

<div class="certificate">

    @foreach (['header', 'body', 'footer'] as $region)
        <div class="cert-{{ $region }}">
            @foreach ($blocks[$region] ?? [] as $block)
                @include("cert.blocks.{$block->type}", [
                    'block' => $block,
                    'context' => $context,
                ])
            @endforeach
        </div>
    @endforeach

</div>

</body>
</html>
