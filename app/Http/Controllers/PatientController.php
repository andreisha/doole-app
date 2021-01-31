<?php

namespace App\Http\Controllers;

use App\Exceptions\PatientNotExistingException;
use App\Repository\PatientRepository;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public const REGEX_PATIENT_ATTRIBUTES = 'required|regex:/^[a-zA-Z\s]{1,100}$/u';

    private PatientRepository $patientRepository;
    private AssignmentController $assignmentController;

    public function __construct(PatientRepository $patientRepository, AssignmentController $assignmentController)
    {
        $this->patientRepository = $patientRepository;
        $this->assignmentController = $assignmentController;
    }

    /**
     * Display all patients
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('patients.patients_list', ['allPatients' => $this->patientRepository->all()]);
    }

    /**
     * Redirect to a view for creating a patient
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('patients.patient_create');
    }

    /**
     * Save a new patient in the database
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'firstName' => self::REGEX_PATIENT_ATTRIBUTES,
                'lastName' => self::REGEX_PATIENT_ATTRIBUTES,
            ]
        );

        $this->patientRepository->create($request->get('firstName'), $request->get('lastName'));

        return redirect('/patients');
    }

    /**
     * Show a page for editing a patient
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function editPage(string $patientId)
    {
        return view('patients.patient_edit', ['patientId' => $patientId]);
    }

    /**
     * Edit a patient
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws PatientNotExistingException
     */
    public function edit(Request $request)
    {
        $request->validate(
            [
                'firstName' => self::REGEX_PATIENT_ATTRIBUTES,
                'lastName' => self::REGEX_PATIENT_ATTRIBUTES,
            ]
        );
        if (!$patient = $this->patientRepository->find($request->post()['patientId'])) {
            throw new PatientNotExistingException();
        }

        $this->patientRepository->edit(
            $request->get('patientId'),
            $request->get('firstName'),
            $request->get('lastName')
        );

        return redirect('/patients');
    }

    /**
     * Delete a patient from database
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws PatientNotExistingException
     */
    public function delete(string $id)
    {
        if (!$patient = $this->patientRepository->find($id)) {
            throw new PatientNotExistingException();
        }
        $this->patientRepository->delete($id);

        return redirect('/patients');
    }
}
