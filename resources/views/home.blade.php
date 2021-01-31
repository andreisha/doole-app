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
        <h1>DOOLE HEALTH.</h1>
        <p class="lead">En esta plataforma puedes crear, ver, editar y suprimir <b>pacientes</b> y <b>departamentos</b>, y también <b>asignar pacientes a departamentos</b>.</p>
        <p class="lead">
            <a href="{{ config('app.url')}}/departments/create" class="btn btn-lg btn-secondary fw-bold border-white bg-white beautiful-button">Crear departamentos</a>
            <a href="{{ config('app.url')}}/patients/create" class="btn btn-lg btn-secondary fw-bold border-white bg-white beautiful-button">Crear pacientes</a>
        </p>
    </main>

    @include('footer')
</div>
</body>
</html>
