<!doctype html>
<html lang="es" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Editar Paciente</title>

    <link href="{{ asset('css/assets/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/doole.css') }}" rel="stylesheet">
</head>
<body class="d-flex h-100 text-center text-white">

<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="mb-auto">
        <div>
            <nav class="nav nav-masthead justify-content-center float-md-end">
                <nav class="nav nav-masthead justify-content-center float-md-end">
                    <a class="nav-link" aria-current="page" href="{{ config('app.url')}}/">Inicio</a>
                    <a class="nav-link" href="{{ config('app.url')}}/departments">Departamentos</a>
                    <a class="nav-link" href="{{ config('app.url')}}/patients">Pacientes</a>
                </nav>
            </nav>
        </div>
    </header>

    <main class="px-7">
        <div class="flex-center position-ref full-height">
            <div class="content">
                <form method="POST" action="{{ config('app.url')}}/patients/{{$patientId}}/edit">
                    @csrf
                    @method('put')
                    <h1>Inserta el nuevo nombre y el nuevo apellido del paciente:</h1>
                    <p></p>
                    <p></p>
                    <div class="form-input">
                        <label class="label-form">Nombre</label>
                        <input
                            type="text"
                            name="firstName"
                            pattern="^[a-zA-Z\s]{1,100}$"
                            class="btn btn-secondary fw-bold border-white bg-white"
                            oninvalid="this.setCustomValidity('El paciente necesita tener un nombre de entre 1 y 100 letras')"
                            onchange="this.setCustomValidity('')"
                            required
                        >
                    </div>
                    <p></p>
                    <p></p>
                    <div class="form-input">
                        <label class="label-form">Apellido</label>
                        <input
                            type="text"
                            name="lastName"
                            pattern="^[a-zA-Z\s]{1,100}$"
                            class="btn btn-secondary fw-bold border-white bg-white"
                            oninvalid="this.setCustomValidity('El paciente necesita tener un apellido de entre 1 y 100 letras')"
                            onchange="this.setCustomValidity('')"
                            required
                        >
                    </div>
                    <input type="hidden" name="patientId" value="{{$patientId}}" >
                    <p></p>
                    <p><i>El nombre y apellido solo pueden contener letras (en minuscula y mayuscula) y espacios, sin acentos.</i></p>
                    <p></p>
                    <button type="submit" class="btn btn-lg btn-secondary fw-bold border-white bg-white">Editar</button>
                </form>
            </div>
        </div>
    </main>

    @include('footer')
</div>
</body>
</html>



