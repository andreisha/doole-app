<!doctype html>
<html lang="es" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Lista de pacientes de un departamento</title>

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
                @if([] === $patientsAndCreatedAt)
                    <h1>El departamento {{ $name }} aún no tiene asignado ningun paciente.</h1>
                @else
                <h1>El departamento {{ $name }} tiene los siguientes pacientes:</h1>
                <div class="table-doole">
                    <table align="center" >
                        <thead>
                        <div class="row">
                            <td class="col-md-4 td-doole table-title"><b>Nombre</b></td>
                            <td class="col-md-4 td-doole table-title"><b>Apellido</b></td>
                            <td class="col-md-4 td-doole table-title"><b>Fecha de asignación</b></td>
                        </div>
                        </thead>
                        <tbody>
                        @foreach ($patientsAndCreatedAt as $data)
                            <tr>
                                <td class="inner-table td-doole">{{ $data['firstName'] }}</td>
                                <td class="inner-table td-doole">{{ $data['lastName'] }}</td>
                                <td class="inner-table td-doole">{{ $data['created_at']}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                @endif
                <p></p>
                <a href="{{ config('app.url')}}/departments/{{$departmentId}}/assignPatients"
                   class="btn btn-lg btn-secondary fw-bold border-white bg-white beautiful-button">Más asignaciones</a>
                <a href="{{ config('app.url')}}/departments/{{$departmentId}}/edit"
                   class="btn btn-lg btn-secondary fw-bold border-white bg-white beautiful-button">Editar departamento</a>
                <a href="{{ config('app.url')}}/departments/{{$departmentId}}/delete"
                       class="btn btn-lg btn-secondary fw-bold border-white bg-white beautiful-button">Suprimir</a>
            </div>
        </div>
    </main>

    @include('footer')
</div>
</body>
</html>



