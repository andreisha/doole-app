<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

abstract class AbstractRepository
{
    protected Model $model;

    protected string $tableName;

    public function __construct(Model $model, string $tableName)
    {
        $this->model = $model;
        $this->tableName = $tableName;
    }

    public function find(string $id): ?stdClass
    {
        return DB::table($this->tableName)->find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model|Model[]|null
     */
    public function all()
    {
        return $this->model->all();
    }

    public function delete(string $id): int
    {
        return DB::table($this->tableName)->delete($id);
    }
}
