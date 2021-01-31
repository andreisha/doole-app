<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Http\Controllers\AssignmentController;
use App\Repository\PatientRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\app\Models\Factory\PatientTestFactory;
use Tests\TestCase;

class PatientControllerTest extends TestCase
{
    use RefreshDatabase;

    private PatientRepository $patientRepository;
    private AssignmentController $assignmentController;

    public function setUp(): void
    {
        parent::setUp();

        $this->createApplication();
        $this->patientRepository = $this->app->make(PatientRepository::class);
        $this->assignmentController = $this->app->make(AssignmentController::class);
        $this->refreshDatabase();
    }

    public function testIndex()
    {
        $patients = [PatientTestFactory::createForTest(), PatientTestFactory::createForTest()];
        $response = $this->withHeaders(['allPatients' => $patients])->get('/patients');

        $response->assertStatus(200);
        $response->assertViewIs('patients.patients_list');
    }

    public function testCreate()
    {
        $response = $this->get('/patients/create');

        $response->assertStatus(200);
        $response->assertViewIs('patients.patient_create');
    }

    /**
     * Test store validation
     * @dataProvider providerTestValidation
     */
    public function testStoreValidation(array $input, int $status)
    {
        $response = $this->post('/patients', ['firstName' => $input['firstName'], 'lastName' => $input['lastName']]);

        $response->assertStatus($status);

        // Redirection status
        if (302 === $status) {
            $response->assertRedirect('/patients');
            DB::table($this->patientRepository::PATIENTS_TABLE_NAME)
                ->where(['firstName' => $input['firstName'], 'lastName' => $input['lastName']])
                ->get()
                ->isNotEmpty();
        }

    }

    public function testEditPage()
    {
        $response = $this->get('/patients/' . PatientTestFactory::createForTest()->id . '/edit');

        $response->assertStatus(200);
        $response->assertViewIs('patients.patient_edit');
    }


    /**
     * Test store validation after edit page
     * @dataProvider providerTestValidation
     */
    public function testEditValidation(array $input, int $status)
    {
        $patient = PatientTestFactory::createForTest(true, 'test', 'test');

        $response = $this->put(
            '/patients/' . (string)$patient->id . '/edit',
            ['patientId' => (string)$patient->id, 'firstName' => $input['firstName'], 'lastName' => $input['lastName']]
        );

        $response->assertStatus($status);

        // Redirection status
        if (302 === $status) {
            $response->assertRedirect('/patients');
            DB::table($this->patientRepository::PATIENTS_TABLE_NAME)
                ->where(['firstName' => $input['firstName'], 'lastName' => $input['lastName']])
                ->get()
                ->isNotEmpty();
        }

    }


    /**
     * Test deletion
     */
    public function testDelete()
    {
        // Valid patient
        $patient = PatientTestFactory::createForTest(true, 'test', 'test');

        $response = $this->get('/patients/' . (string)$patient->id . '/delete');

        $response->assertStatus(302);
        $response->assertRedirect('/patients');
        $this->assertNull(DB::table($this->patientRepository::PATIENTS_TABLE_NAME)->find((string)$patient->id));

        // Invalid patient
        $this->get('/patients/' . 'fakeId' . '/delete')->assertStatus(500);
    }


    public function providerTestValidation()
    {
        $string101characters = 'qdgtlmvvpuaudybqgosjieesremhnrnjoorctdfpgliukkbkwiktjfwiimwtknznqtjzcrmhfxiqzemostifrqdfzgjjiejepavus';

        // Valid
        yield [
            'Valid firstName and valid lastName' => [
                'firstName' => PatientTestFactory::FIRST_NAME_TEST,
                'lastName' => PatientTestFactory::LAST_NAME_TEST
            ],
            302
        ];
        // Invalid because of firstName
        yield [
            'Valid firstName but invalid lastName (null)' => [
                'firstName' => null,
                'lastName' => PatientTestFactory::LAST_NAME_TEST
            ],
            500
        ];
        yield [
            'Valid firstName but invalid lastName (too long)' => [
                'firstName' => $string101characters,
                'lastName' => PatientTestFactory::LAST_NAME_TEST
            ],
            500
        ];
        yield [
            'Valid firstName but invalid lastName (too short)' => [
                'firstName' => PatientTestFactory::FIRST_NAME_TEST,
                'lastName' => ''
            ],
            500
        ];
        yield [
            'Valid firstName but invalid lastName (containing numbers)' => [
                'firstName' => 'hello100',
                'lastName' => PatientTestFactory::LAST_NAME_TEST
            ],
            500
        ];
        yield [
            'Valid firstName but invalid lastName (containing special characters)' => [
                'firstName' => 'hello!$/%)(="|',
                'lastName' => PatientTestFactory::LAST_NAME_TEST
            ],
            500
        ];
        // Invalid because of lastName
        yield [
            'Valid firstName but invalid lastName (null)' => [
                'firstName' => PatientTestFactory::FIRST_NAME_TEST,
                'lastName' => null
            ],
            500
        ];
        yield [
            'Valid firstName but invalid lastName (too long)' => [
                'firstName' => PatientTestFactory::FIRST_NAME_TEST,
                'lastName' => $string101characters
            ],
            500
        ];
        yield [
            'Valid firstName but invalid lastName (too short)' => [
                'firstName' => PatientTestFactory::FIRST_NAME_TEST,
                'lastName' => ''
            ],
            500
        ];
        yield [
            'Valid firstName but invalid lastName (containing numbers)' => [
                'firstName' => PatientTestFactory::FIRST_NAME_TEST,
                'lastName' => 'hello100'
            ],
            500
        ];
        yield [
            'Valid firstName but invalid lastName (containing special characters)' => [
                'firstName' => PatientTestFactory::FIRST_NAME_TEST,
                'lastName' => 'hello!$/%)(="|'
            ],
            500
        ];
    }
}
