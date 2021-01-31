<?php

namespace Tests\Unit\Repository;

use App\Repository\DepartmentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Tests\app\Models\Factory\DepartmentTestFactory;
use Tests\TestCase;

class DepartmentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private DepartmentRepository $departmentRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->createApplication();

        $this->departmentRepository = $this->app->make(DepartmentRepository::class);
        $this->refreshDatabase();
    }

    public function testCreate()
    {
        $department = $this->departmentRepository->create(DepartmentTestFactory::NAME_TEST);

        $this->assertTrue(Uuid::isValid($department->id));
        $this->assertSame(DepartmentTestFactory::NAME_TEST, $department->name);
    }


    public function testEdit(): void
    {
        $department = DepartmentTestFactory::createForTest(true);
        $newName = 'newName';

        $this->departmentRepository->edit($department->id, $newName);

        $department->refresh();

        $this->assertSame($newName, $department->name);
    }
}
