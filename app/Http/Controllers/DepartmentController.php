<?php

namespace App\Http\Controllers;

use App\Exceptions\DepartmentNotExistingException;
use App\Repository\DepartmentRepository;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public const REGEX_DEPARTMENT_ATTRIBUTES = 'required|regex:/^[a-zA-Z\s]{1,100}$/u';

    private DepartmentRepository $departmentRepository;
    private AssignmentController $assignmentController;

    public function __construct(DepartmentRepository $departmentRepository, AssignmentController $assignmentController)
    {
        $this->departmentRepository = $departmentRepository;
        $this->assignmentController = $assignmentController;
    }

    /**
     * Display all departments
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('departments.departments_list', ['allDepartments' => $this->departmentRepository->all()]);
    }

    /**
     * Redirect to a view for creating a department
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('departments.department_create');
    }

    /**
     * Save a new department in the database
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $request->validate(['name' => self::REGEX_DEPARTMENT_ATTRIBUTES]);

        $this->departmentRepository->create($request->get('name'));

        return redirect('/departments');
    }

    /**
     * Show a page for editing a department
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function editPage(string $departmentId)
    {
        return view('departments.department_edit', ['departmentId' => $departmentId]);
    }

    /**
     * Edit a department
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function edit(Request $request)
    {
        $request->validate(['name' => self::REGEX_DEPARTMENT_ATTRIBUTES]);

        if (!$department = $this->departmentRepository->find($request->post()['departmentId'])) {
            throw new DepartmentNotExistingException();
        }

        $this->departmentRepository->edit(
            $request->get('departmentId'),
            $request->get('name')
        );

        return redirect('/departments');
    }

    /**
     * Delete a department from database
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws DepartmentNotExistingException
     */
    public function delete(string $id)
    {
        if (!$department = $this->departmentRepository->find($id)) {
            throw new DepartmentNotExistingException();
        }
        $this->departmentRepository->delete($id);

        return redirect('/departments');
    }
}
