<?php

namespace app\widgets\crudgrid;

use Yii;
use yii\grid\Column;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * Class CrudGrid
 * @property string $dataColumnClass Defaults to app\widgets\NewRowDataColumn.
 * @package app\widgets\crudgrid
 */
class CrudGrid extends GridView
{
    public $newRowModel;

    /**
     * Initializes the grid view.
     * This method will initialize required property values and instantiate [[columns]] objects.
     */
    public function init()
    {
        if (!$this->dataColumnClass)
            $this->dataColumnClass = NewRowDataColumn::className();
        parent::init();
    }

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
            } else {
                /* @var $column Column */
                $inputCells []= Html::tag('td',$this->emptyCell);
            }
        }
        return implode('', $inputCells);
    }


}