<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="/css/all.min.css" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-2 sm:pt-0 bg-white">
        <div class="w-[42rem]  px-6 py-4 shadow-md overflow-hidden sm:rounded-lg text-center bg-gray-100">
                <form method="post">
                    <div>
                        input x:<br>
                        <input type="text" name="user" id="user" required="" class="rounded border-solid border-2 border-indigo-200  hover:border-indigo-400">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</body>

</html>
