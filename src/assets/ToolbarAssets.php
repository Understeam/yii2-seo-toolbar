<?php

namespace understeam\seotoolbar\assets;

use yii\web\AssetBundle;

/**
 * Class ToolbarAssets TODO: WRITE CLASS DESCRIPTION
 * @author Anatoly Rugalev <rugalev@enaza.ru>
 */
class ToolbarAssets extends AssetBundle
{

    public $css = [
        'toolbar.css'
    ];

    public $js = [
        'toolbar.js',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/files';
        parent::init();
    }


}