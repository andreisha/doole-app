<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Http\Controllers\AssignmentController;
use App\Repository\DepartmentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\app\Models\Factory\DepartmentTestFactory;
use Tests\TestCase;

class DepartmentControllerTest extends TestCase
{
    use RefreshDatabase;

    private DepartmentRepository $departmentRepository;
    private AssignmentController $assignmentController;

    public function setUp(): void
    {
        parent::setUp();

        $this->createApplication();
        $this->departmentRepository = $this->app->make(DepartmentRepository::class);
        $this->assignmentController = $this->app->make(AssignmentController::class);
        $this->refreshDatabase();
    }

    public function testIndex()
    {
        $departments = [DepartmentTestFactory::createForTest(), DepartmentTestFactory::createForTest()];
        $response = $this->withHeaders(['allDepartments' => $departments])->get('/departments');

        $response->assertStatus(200);
        $response->assertViewIs('departments.departments_list');
    }

    public function testCreate()
    {
        $response = $this->get('/departments/create');

        $response->assertStatus(200);
        $response->assertViewIs('departments.department_create');
    }

    /**
     * Test store validation
     * @dataProvider providerTestValidation
     */
    public function testStoreValidation(array $input, int $status)
    {
        $response = $this->post('/departments', ['name' => $input['name']]);

        $response->assertStatus($status);

        // Redirection status
        if (302 === $status) {
            $response->assertRedirect('/departments');
            DB::table($this->departmentRepository::DEPARTMENTS_TABLE_NAME)
                ->where(['name' => $input['name']])
                ->get()
                ->isNotEmpty();
        }
    }

    public function testEditPage()
    {
        $response = $this->get('/departments/' . DepartmentTestFactory::createForTest()->id . '/edit');

        $response->assertStatus(200);
        $response->assertViewIs('departments.department_edit');
    }


    /**
     * Test store validation after edit page
     * @dataProvider providerTestValidation
     */
    public function testEditValidation(array $input, int $status)
    {
        $department = DepartmentTestFactory::createForTest(true, 'test');

        $response = $this->put(
            '/departments/' . (string)$department->id . '/edit',
            ['departmentId' => (string)$department->id, 'name' => $input['name']]
        );

        $response->assertStatus($status);

        // Redirection status
        if (302 === $status) {
            $response->assertRedirect('/departments');
            DB::table($this->departmentRepository::DEPARTMENTS_TABLE_NAME)
                ->where(['name' => $input['name']])
                ->get()
                ->isNotEmpty();
        }
    }

    /**
     * Test deletion
     */
    public function testDelete()
    {
        // Valid department
        $department = DepartmentTestFactory::createForTest(true, 'test');

        $response = $this->get('/departments/' . (string)$department->id . '/delete');

        $response->assertStatus(302);
        $response->assertRedirect('/departments');
        $this->assertNull(DB::table($this->departmentRepository::DEPARTMENTS_TABLE_NAME)->find((string)$department->id));

        // Invalid department
        $this->get('/departments/' . 'fakeId' . '/delete')->assertStatus(500);
    }


    public function providerTestValidation()
    {
        $string101characters = 'qdgtlmvvpuaudybqgosjieesremhnrnjoorctdfpgliukkbkwiktjfwiimwtknznqtjzcrmhfxiqzemostifrqdfzgjjiejepavus';

        // Valid
        yield [
            'Valid name' => [
                'name' => DepartmentTestFactory::NAME_TEST,
            ],
            302
        ];
        // Invalid
        yield [
            'Invalid name (null)' => [
                'name' => null,
            ],
            500
        ];
        yield [
            'Invalid name (too long)' => [
                'name' => $string101characters
            ],
            500
        ];
        yield [
            'Invalid name  (too short)' => [
                'name' => ''
            ],
            500
        ];
        yield [
            'Invalid name  (containing numbers)' => [
                'name' => 'hello100'
            ],
            500
        ];
        yield [
            'Invalid name  (containing special characters)' => [
                'name' => 'hello!$/%)(="|'
            ],
            500
        ];
    }

}
