<?php

namespace understeam\seotoolbar\behaviors;

use yii\base\Behavior;
use yii\helpers\Url;
use yii\web\View;

/**
 * Class ToolbarBehavior TODO: WRITE CLASS DESCRIPTION
 * @author Anatoly Rugalev <rugalev@enaza.ru>
 */
class ToolbarBehavior extends Behavior
{

    /** @var View */
    public $owner;

    public function events()
    {
        return [
            View::EVENT_BEFORE_RENDER => 'beforeRender',
        ];
    }

    public function beforeRender()
    {
        $url = Url::to(['/seoToolbar/toolbar/index']);
        $this->owner->registerJs(<<<JAVASCRIPT
$.get("{$url}", function(data) {
    $('body').append(data);
});
JAVASCRIPT
        , View::POS_READY);

    }


}