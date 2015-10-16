<?php

namespace app\widgets;

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/**
 * This creates an ActiveForm and a GridView within separate Pjax containers. Submitting the form updates the gridview.
 * @package app\widgets
 */
class GridForm extends ActiveForm
{
    /**
     * The grid position to be used when the gridview must be shown BEFORE the form.
     */
    const POS_GRID_BEFORE = 1;
    /**
     * The grid position to be used when the gridview must be shown AFTER the form.
     */
    const POS_GRID_AFTER = 2;

    /**
     * @var array The options for the form's pjax container.
     */
    public $formPjaxOptions = [];
    /**
     * @var array The options for the gridview's pjax container.
     */
    public $gridPjaxOptions = [];
    /**
     * @var array The options for the GridView widget.
     */
    public $gridOptions = [];

    /**
     * @var int Either POS_GRID_BEFORE or POS_GRID_AFTER. Default is AFTER.
     */
    public $gridPosition;

    /**
     * Initializes required config, echoes the starting html tags and returns the ActiveForm object.
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        if (!isset($this->gridPosition)) {
            $this->gridPosition = static::POS_GRID_AFTER;
        }
        $this->options = ArrayHelper::merge([
            'data-pjax' => true,
        ], $this->options);
        $this->formPjaxOptions = ArrayHelper::merge([
            'id' => $this->options['id']."-pjax-form",
        ], $this->formPjaxOptions);
        $this->gridPjaxOptions = ArrayHelper::merge([
            'id' => $this->options['id']."-pjax-grid",
        ], $this->gridPjaxOptions);

        if ($this->gridPosition === static::POS_GRID_BEFORE) {
            $this->generateGrid();
        }
        Pjax::begin($this->formPjaxOptions);
        parent::init();
    }

    /**
     * Ends the started HTML tags and register the JS script that links GridView reload to form submit.
     */
    public function run()
    {
        parent::run();
        Pjax::end();

        if ($this->gridPosition === static::POS_GRID_AFTER) {
            $this->generateGrid();
        }

        $formPjaxId = $this->formPjaxOptions['id'];
        $gridPjaxId = $this->gridPjaxOptions['id'];
        $this->view->registerJs("
            // Reload link grid after submitting a new link
            $('#$formPjaxId').on('pjax:end', function () {
                $.pjax.reload({container: '#$gridPjaxId'});
            });
        ");
    }

    /**
     * Echoes the GridView widget.
     */
    protected function generateGrid()
    {
        Pjax::begin($this->gridPjaxOptions);
        echo GridView::widget($this->gridOptions);
        Pjax::end();
    }
}