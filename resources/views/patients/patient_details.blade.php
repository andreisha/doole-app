<!doctype html>
<html lang="es" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Lista de departamentos para un paciente</title>

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
                @if(empty($departmentsAndCreatedAt))
                    <h1>{{ $firstName }} {{ $lastName }} aún no tiene asignado ningun departamento.</h1>
                @else
                    <h1>{{ $firstName }} {{ $lastName }} pertenece a los siguientes departamentos:</h1>
                    <div class="table-doole">
                        <table align="center">
                            <thead>
                            <div class="row">
                                <td class="col-md-6 td-doole table-title"><b>Departamento</b></td>
                                <td class="col-md-6 td-doole table-title"><b>Fecha de asignación</b></td>
                            </div>
                            </thead>
                            <tbody>
                            @foreach ($departmentsAndCreatedAt as $data)
                                <tr>
                                    <td class="inner-table td-doole">{{ $data['name'] }}</td>
                                    <td class="inner-table td-doole">{{ $data['created_at']}}</td>{{--
                                <td class="inner-table td-doole">{{ $assignment->created_at }}</td>--}}
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                @endif
                <p></p>
                <a href="{{ config('app.url')}}/patients/{{$patientId}}/assignDepartments"
                   class="btn btn-lg btn-secondary fw-bold border-white bg-white beautiful-button">Más asignaciones</a>
                <a href="{{ config('app.url')}}/patients/{{$patientId}}/edit"
                   class="btn btn-lg btn-secondary fw-bold border-white bg-white beautiful-button">Editar paciente</a>
                <a href="{{ config('app.url')}}/patients/{{$patientId}}/delete"
                   class="btn btn-lg btn-secondary fw-bold border-white bg-white beautiful-button">Suprimir paciente</a>
            </div>
        </div>
    </main>

    @include('footer')
</div>
</body>
</html>



