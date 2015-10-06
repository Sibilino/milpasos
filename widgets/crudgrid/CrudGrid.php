<?php

namespace app\widgets\crudgrid;

use Yii;
use yii\base\Model;
use yii\grid\ActionColumn;
use yii\grid\Column;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class CrudGrid
 * @property string $dataColumnClass Defaults to app\widgets\NewRowDataColumn.
 * @package app\widgets\crudgrid
 */
class CrudGrid extends GridView
{
    /**
     * @var Model
     */
    public $newRowModel;

    /**
     * Renders the table body.
     * @return string the rendering result.
     */
    public function renderTableBody()
    {
        $body = str_replace('</tbody>', '', parent::renderTableBody()); // Remove tbody end tag
        $inputRow = $this->renderInputRow();

        // TODO: Add configurable html options to input row
        $body .= Html::tag('tr', $inputRow, ['id' => "$this->id-new-row-inputs"]);

        return "$body</tbody>";
    }

    protected function renderInputRow()
    {
        $model = $this->newRowModel;
        $inputCells = [];
        foreach ($this->columns as $column) {

            if ($column instanceof NewRowColumn) {
                $inputCells []= $column->renderNewRowCell($model);

            } elseif ($column instanceof DataColumn) {
                $inputCells []= $this->renderDataCell($model, $column);

            } elseif ($column instanceof ActionColumn) {
                $inputCells []= $this->renderActionCell($model, $column);

            } else {
                /* @var $column Column */
                $inputCells []= Html::tag('td',$this->emptyCell);
            }
        }
        return implode('', $inputCells);
    }

    /**
     * @param Model $model
     * @param Column|DataColumn $column
     * @return string
     */
    protected function renderDataCell(Model $model, DataColumn $column)
    {
        if ($column->attribute !== null && $model->isAttributeActive($column->attribute)) {
            $input = Html::activeTextInput($model, $column->attribute);
            $error = $model->hasErrors($column->attribute) ? ' ' . Html::error($model, $column->attribute) : '';
            return Html::tag('td', $input . $error);
        }
        return Html::tag('td',$this->emptyCell);
    }

    /**
     * @param Model $model
     * @param ActionColumn|Column $column
     * @return string
     */
    protected function renderActionCell(Model $model, ActionColumn $column)
    {
        $controller = $column->controller;
        if (!$controller) {
            $reflection = new \ReflectionClass($model);
            $controller = strtolower($reflection->getShortName());
        }
        $options = [
            'title' => Yii::t('yii', 'Add'),
            'aria-label' => Yii::t('yii', 'Add'),
            'data-pjax' => '0',
        ];
        $button = Html::a('<span class="glyphicon glyphicon-plus"></span>', Url::to(["$controller/create"]), $options);
        return Html::tag('td', $button);
    }
}