<!doctype html>
<html lang="es" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Lista de Departamentos posibles para un Paciente</title>

    <link href="{{ asset('css/assets/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/doole.css') }}" rel="stylesheet">
</head>
<body class="d-flex h-100 text-center text-white">

<div class="cover-container-lists d-flex w-100 h-100 p-3 mx-auto flex-column">
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
        <div class="flex-center position-center full-height">
            <div class="content">
                @if(empty($allPatients))
                    <h1>Este departamento {{$name}} ya tiene a todos los pacientes asignados.</h1>
                @else
                <h1>Elige los pacientes para el departamento {{$name}}</h1>
                <form  method="POST" action="{{ config('app.url')}}/departments/{{$departmentId}}/assignPatientsToDepartment">
                    @csrf
                    <div class="table-doole">
                        <table align="center" >
                            <thead>
                            <div class="row mb-3">
                                <td class="col-md-2 td-doole table-title"><b>Escoger</b></td>
                                <td class="col-md-7 td-doole table-title"><b>Nombre</b></td>
                                <td class="col-md-7 td-doole table-title"><b>Apellido</b></td>
                                <td class="col-md-3 td-doole table-title"><b>Detalles</b></td>
                            </div>
                            </thead>
                            <tbody>
                            @foreach ($allPatients as $patient)
                                <tr>
                                    <td class="inner-table td-doole"><input type="checkbox" name="patients[]" id="patient" value="{{$patient->id}}"></td>
                                    <td class="inner-table td-doole">{{ $patient->firstName }}</td>
                                    <td class="inner-table td-doole">{{ $patient->lastName }}</td>
                                    <td class="inner-table td-doole">
                                        <a href="{{ config('app.url')}}/patients/{{$patient->id}}">
                                            <img src="{{ asset('img/eye.png') }}" alt="details" width="20" height="15">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <p></p>
                        <button type="submit" class="btn btn-lg btn-secondary fw-bold border-white bg-white">Asignar</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </main>

    @include('footer')
</div>
</body>
</html>



