<?php

namespace app\widgets\crudgrid;

use yii\base\Model;

interface NewRowColumn
{
    public function renderNewRowCell(Model $model);
}