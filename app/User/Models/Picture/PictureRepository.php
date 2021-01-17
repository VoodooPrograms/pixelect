<?php

namespace Quetzal\User\Models\Picture;

use Quetzal\Core\Database\Models\Model;
use Quetzal\Core\Database\Models\Repository;

class PictureRepository extends Repository
{

    public function update(array $data, int $id)
    {
        // TODO: Implement update() method.
    }

    protected function doCreateObject(array $data): Model
    {
        // TODO: Implement doCreateObject() method.
    }

    protected function doInsert(Model $model)
    {
        // TODO: Implement doInsert() method.
    }

    protected function selectStmt(): \PDOStatement
    {
        // TODO: Implement selectStmt() method.
    }

    protected function targetClass(): string
    {
        // TODO: Implement targetClass() method.
    }
}