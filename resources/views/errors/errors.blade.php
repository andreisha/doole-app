<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>DOOLE HEALTH</title>
    <link href="{{ asset('css/assets/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/doole.css') }}" rel="stylesheet">
</head>
<body class="d-flex h-100 text-center text-white">

<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="mb-auto">
        <div>
            <nav class="nav nav-masthead justify-content-center float-md-end">
                <a class="nav-link active" aria-current="page" href="{{ config('app.url')}}/">Inicio</a>
                <a class="nav-link" href="{{ config('app.url')}}/departments">Departamentos</a>
                <a class="nav-link" href="{{ config('app.url')}}/patients">Pacientes</a>
            </nav>
        </div>
    </header>

    <main class="px-3">
        <p class="lead">Ha habido un error, por favor vuelve a intentarlo nuevamente.</p>
    </main>

    @include('footer')
</div>


</body>
</html>
