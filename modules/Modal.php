<?php

namespace Zelenin\yii\SemanticUI\modules;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use Zelenin\yii\SemanticUI\Elements;
use Zelenin\yii\SemanticUI\Widget;

class Modal extends Widget
{
    public $closeButton = '<i class="close icon"></i>';

    public $header;
    public $headerOptions = [];

    public $content;
    public $contentOptions = [];

    public $actions;
    public $actionsOptions = [];

    public $type = self::TYPE_STANDARD;
    const TYPE_BASIC = 'basic';
    const TYPE_STANDARD = 'standard';

    public $fullscreen = false;
    const TYPE_FULLSCREEN = 'fullscreen';

    public $size;
    const SIZE_LARGE = 'large';
    const SIZE_SMALL = 'small';

    public function init()
    {
        parent::init();

        $this->registerJs();

        Html::addCssClass($this->options, 'ui ' . $this->type . ' modal');
        if ($this->fullscreen) {
            Html::addCssClass($this->options, self::TYPE_FULLSCREEN);
        }
        if ($this->size) {
            Html::addCssClass($this->options, $this->size);
        }

        echo Html::beginTag('div', $this->options);
        echo $this->renderCloseButton();
        echo $this->renderHeader();
        Html::addCssClass($this->contentOptions, 'content');
        echo Html::beginTag('div', $this->contentOptions);
    }

    public function run()
    {
        echo Html::endTag('div');
        echo $this->renderActions();
        echo Html::endTag('div');
    }

    public function renderCloseButton()
    {
        return $this->closeButton;
    }

    public function renderHeader()
    {
        Html::addCssClass($this->headerOptions, 'header');
        return $this->header ? Html::tag('div', $this->header, $this->headerOptions) : null;
    }

    public function renderActions()
    {
        Html::addCssClass($this->actionsOptions, 'actions');
        return $this->actions ? Html::tag('div', $this->actions, $this->actionsOptions) : null;
    }

    public function renderToggleButton($content, $options = [])
    {
        if (!isset($options['id'])) {
            $options['id'] = $this->getId() . '-button';
        }

        $this->getView()->registerJs('
        jQuery("#' . $options['id'] . '").on("click", function(event) {
            event.preventDefault();
            jQuery("#' . $this->getId() . '").modal("show");
        });
        ');

        return Elements::button($content, $options);
    }

    public function registerJs()
    {
        $this->registerJsAsset();
        $clientOptions = $this->clientOptions ? Json::encode($this->clientOptions) : null;
        if ($clientOptions) {
            $this->getView()->registerJs('jQuery("#' . $this->getId() . '").modal(' . $clientOptions . ');');
        }
    }
}