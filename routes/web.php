<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

/** Home */
Route::get('/', function () {
    return view('home');
});

/** Patients */
Route::get('/patients/create', [PatientController::class, 'create']);
Route::get('/patients', [PatientController::class, 'index']);
Route::post('/patients', [PatientController::class, 'store']);
Route::get('/patients/{id}/edit', [PatientController::class, 'editPage']);
Route::put('/patients/{id}/edit', [PatientController::class, 'edit']);
Route::get('/patients/{id}/delete', [PatientController::class, 'delete']);

/** Departments */
Route::get('/departments/create', [DepartmentController::class, 'create']);
Route::get('/departments', [DepartmentController::class, 'index']);
Route::post('/departments', [DepartmentController::class, 'store']);
Route::get('/departments/{id}/edit', [DepartmentController::class, 'editPage']);
Route::put('/departments/{id}/edit', [DepartmentController::class, 'edit']);
Route::get('/departments/{id}/delete', [DepartmentController::class, 'delete']);

/** Assignments */
Route::get('/patients/{id}/assignDepartments', [AssignmentController::class, 'listPossibleDepartmentsForPatient']);
Route::post('/patients/{id}/assignDepartmentsToPatient', [AssignmentController::class, 'assignDepartmentsToPatient']);
Route::get('/departments/{id}/assignPatients', [AssignmentController::class, 'listPossiblePatientsForDepartment']);
Route::post('/departments/{id}/assignPatientsToDepartment', [AssignmentController::class, 'assignPatientsToDepartment']);
Route::get('/patients/{id}', [AssignmentController::class, 'showDepartmentsForPatient']);
Route::get('/departments/{id}', [AssignmentController::class, 'showPatientsForDepartment']);
