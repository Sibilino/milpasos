<?php

namespace app\widgets\crudgrid;


use yii\base\Model;
use yii\grid\DataColumn;
use yii\helpers\Html;

class NewRowDataColumn extends DataColumn implements NewRowColumn
{
    public function renderNewRowCell(Model $model)
    {
        if ($this->attribute !== null && $model->isAttributeActive($this->attribute)) {
            $input = Html::activeTextInput($model, $this->attribute);
            $error = $model->hasErrors($this->attribute) ? ' '.Html::error($model, $this->attribute) : '';
            return Html::tag('td', $input.$error);
        }
        return $this->grid->emptyCell;
    }
}