<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @include('components/css')
</head>
<body class="antialiased">
    @include('components/navbar')
    @include('components/subnavbar')
    <main role="main" class="wrapper" id="whole-content">
        {{ $slot }}
    </main>
    <main class="wrapper">
        <div id="tableContent"></div>
        <div id="dialog-form"></div>
        <div id="sub-content"></div>
    </main>
    @include('components/js')
</body>
</html>