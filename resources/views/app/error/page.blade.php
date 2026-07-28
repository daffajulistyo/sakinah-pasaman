<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/satoshi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>SAKINAH | {{ $code }}</title>
</head>
<body>
    <div class="flex h-screen items-center justify-center bg-boxdark-2 p-4">
        <div class="text-center">
            <h1 class="text-8xl font-bold text-white/10">{{ $code }}</h1>
            <h2 class="mt-4 text-2xl font-bold text-white">{{ $title }}</h2>
            <p class="mt-2 text-bodydark2">{{ $message }}</p>
            <a href="{{ url('/home') }}"
                class="mt-6 inline-block rounded-lg bg-primary px-6 py-2.5 font-medium text-white hover:bg-opacity-90 transition-colors">
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>

