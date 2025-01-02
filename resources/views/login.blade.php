<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 10 Custom Login and Registration - Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="/css/app.css" rel="stylesheet">
    <link href="/js/app.js" rel="stylesheet">
</head>

<body>
    <div class="row justify-content-center">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-2 sm:pt-0 bg-white">
            <div class="w-[22rem] px-6 py-4 shadow-md overflow-hidden sm:rounded-lg text-center bg-gray-100">
                    @if (Session::has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::get('error') }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <br>
                            <label for="username" class="form-label">Código de barras:</label>
                            <input type="text" name="username" id="username" required=""
                        class="form-control rounded border-solid border-2 border-indigo-200  hover:border-indigo-400">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña:</label>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>
                        <div class="mb-3">
                            <div class="">
                                <button type="submit"
                                    class="btn m-2 p-2 px-3 btn rounded bg-blue-700 text-white">Ingresar</button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</body>

</html>
