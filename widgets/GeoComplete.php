<?php

namespace app\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/**
 * Class GeoComplete. Generates an address search bar that will geocode the search term using Google Maps. The geocoding
 * data will be set in hidden fields.
 * @package app\widgets
 */
class GeoComplete extends AutoComplete
{

    /**
     * @var string The model attribute that will receive the longitude coordinate.
     */
    public $lonAttribute = 'lon';
    /**
     * @var string The model attribute that will receive the latitude coordinate.
     */
    public $latAttribute = 'lat';
    /**
     * @inheritdoc
     * @throws InvalidConfigException If the widget config does not contain a model and attribute pair.
     */
    public function init()
    {
        if (!$this->hasModel())
            throw new InvalidConfigException(self::className()." requires a Model and an Attribute.");
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        MapsAsset::register($this->view); // Register Google Maps API

        $this->view->registerJs("var geocoder = new google.maps.Geocoder();");

        $hiddenContainer = $this->getHiddenDivId();
        $hiddenInput = $this->getHiddenInputId();
        $lonInputName = Html::getInputName($this->model, $this->lonAttribute);
        $latInputName = Html::getInputName($this->model, $this->latAttribute);

        $this->clientOptions = ArrayHelper::merge([
            // Upon searching for a term, call geocoding API and store results in hidden fields
            // Previous results are also cleared
            'source' => new JsExpression("function (request, response) {
                $('#$hiddenContainer input.search-result').remove();
                $('#$hiddenInput').val('');
                geocoder.geocode({'address': request.term}, function(results, status) {
                    var resultAddresses = [];
                    for (var i=0; i<results.length; i++) {
                        var result = results[i];
                        resultAddresses.push(result.formatted_address);
                        $('#$hiddenContainer')
                            .append('<input class=\"search-result\" result-ref=\"'+result.formatted_address+'\" type=\"hidden\" name=\"$lonInputName\" value=\"'+result.geometry.location.lng()+'\">')
                            .append('<input class=\"search-result\" result-ref=\"'+result.formatted_address+'\" type=\"hidden\" name=\"$latInputName\" value=\"'+result.geometry.location.lat()+'\">')
                        ;
                    }
                    response(resultAddresses);
                });
            }"),
            // Upon selecting a result, remove unused geocoding data from the other results, and set hidden input
            'select' => new JsExpression("function (event,ui) {
                $('#$hiddenContainer input.search-result').each(function () {
                    if ($(this).attr('result-ref') != ui.item.label) {
                        $(this).remove();
                    }
                });
                $('#$hiddenInput').val(ui.item.label);
            }"),
        ], $this->options);

        parent::run();
    }

    /**
     * Generates a search bar input (unrelated to the given model) and hidden inputs to set the model's attributes.
     * @return string The html to render.
     */
    public function renderWidget()
    {
        // Generate "selector", which is an additional input that only serves as the address search bar
        $html = Html::activeTextInput($this->model, $this->attribute, ArrayHelper::merge($this->options, [
            'name' => "search-".$this->options['id'],
        ]));

        // The real address will be set into the hidden inputs below
        $html .= Html::beginTag('div', [
            'id' => $this->getHiddenDivId(),
        ]);
        $html .= Html::activeHiddenInput($this->model, $this->attribute, ArrayHelper::merge($this->options, [
            'id' => $this->getHiddenInputId(),
        ]));
        $html .= Html::endTag('div');

        return $html;
    }

    /**
     * The id to identify the div that contains the hidden inputs.
     * @return string
     */
    protected function getHiddenDivId()
    {
        return $this->options['id']."-hidden-container";
    }

    /**
     * The id of the hidden "address" input.
     * @return string
     */
    protected function getHiddenInputId()
    {
        return $this->options['id']."-hidden";
    }
}