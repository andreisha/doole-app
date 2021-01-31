<?php

namespace Tests\Unit\Repository;

use App\Repository\AbstractRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AbstractRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public const TABLE_NAME = 'test_abstract_repository';
    public const ROW_ID = 'test';

    public function testFind()
    {
        $this->assertNotNull($this->createNewAnonymousClassFromAbstract()->find(self::ROW_ID));
        $this->dropTable();
    }

    public function testDelete()
    {
        $this->assertNotNull($this->createNewAnonymousClassFromAbstract()->delete(self::ROW_ID));
        $this->assertNull(DB::table(self::TABLE_NAME)->find('test'));
        $this->dropTable();
    }

    private function createNewAnonymousClassFromAbstract(): AbstractRepository
    {
        return new class () extends AbstractRepository {
            public function __construct()
            {
                parent::__construct(
                    new class () extends Model {
                        public function __construct()
                        {
                            parent::__construct();

                            Schema::create(
                                AbstractRepositoryTest::TABLE_NAME,
                                function (Blueprint $table) {
                                    $table->string('id')->primary();
                                }
                            );
                            DB::table(AbstractRepositoryTest::TABLE_NAME)->insert(
                                array('id' => AbstractRepositoryTest::ROW_ID)
                            );
                        }
                    },
                    AbstractRepositoryTest::TABLE_NAME
                );
            }
        };
    }

    private function dropTable(): void
    {
        Schema::drop(self::TABLE_NAME);
    }
}
