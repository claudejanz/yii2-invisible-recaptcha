<?php

namespace claudejanz\yii2invisiblerecaptcha;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvisibleRecaptcha
 *
 * @author Claude
 */
class InvisibleRecaptcha extends Widget
{

    /**
     * Google reCaptcha api file url.
     */
    public $api_url = '//www.google.com/recaptcha/api.js';

    /**
     * @var string   reCaptcha siteKey.
     */
    public $siteKey;

    /**
     * @var string   reCaptcha secret key.
     */
    public $secret;
    private $callback;

    public function init()
    {
        $this->_checkConfig();
$this->_registerScript();


        
        parent::init();
    }

    public function run()
    {
        echo Html::beginTag('div', [
            'class' => 'g-recaptcha',
            'data' => [
                'sitekey' => $this->siteKey,
                'callback' => $this->callback,
                'size' => 'invisible',
            ]
        ]);
        echo Html::endTag('div');
        parent::run();
    }

    private function _checkConfig()
    {
        if (empty(Yii::$app->captcha)) {
            throw new InvalidConfigException('Required `captcha` component isn\'t set.');
        }

        if (empty(Yii::$app->captcha->siteKey)) {
            throw new InvalidConfigException('Required `siteKey` param isn\'t set.');
        }
    }

    private function _registerScript()
    {
        $this->callback = 'callback' . $this->getId();
        $view = $this->view;
        $view->registerJs($this->callback . '(){'
                . 'grecaptcha.execute();'
                . '}');
        $view->registerJsFile($this->api_url, [
            'defer'=>true,
            'async '=>true,
            'onload'=>$this->callback,
            ]);
        
    }

}
