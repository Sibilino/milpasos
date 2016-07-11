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
     * Initializes required config, echoes the starting html tags and returns the ActiveForm object.
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
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

        $this->generateGrid();

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
