<?php

namespace app\widgets\assets;

/**
 * Assets for the EventViewer widget.
 * @package app\widgets\assets
 */
class EventViewerBundle extends AngularBundle
{
    public $depends = [
        'app\assets\AngularJsAsset',
        'app\assets\MilpasosAsset',
    ];
    public $js = [
        'eventViewer.js',
    ];
}