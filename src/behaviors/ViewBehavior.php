<?php

namespace understeam\seotoolbar\behaviors;

use understeam\seotoolbar\models\Page;
use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\Model;
use yii\base\ViewEvent;
use yii\web\View;

/**
 * Class ViewBehavior TODO: WRITE CLASS DESCRIPTION
 * @author Anatoly Rugalev <rugalev@enaza.ru>
 */
class ViewBehavior extends Behavior
{

    /**
     * @var View
     */
    public $owner;

    /**
     * @var Page
     */
    private static $_seoPage;

    public function events()
    {
        return [
            View::EVENT_BEFORE_RENDER => 'beforeRender',
            View::EVENT_BEGIN_PAGE => 'beginPage',
        ];
    }

    public function beforeRender(ViewEvent $event)
    {
        if (!isset(self::$_seoPage)) {
            self::$_seoPage = Page::findByUrl(Yii::$app->request->url);
        }
        if (self::$_seoPage) {
            $params = $event->params;
            foreach ($params as $key => $param) {
                if ($param instanceof Model && $param->hasMethod('getSeo')) {
                    self::$_seoPage->addModel($param, $key);
                }
            }
        }
    }

    public function beginPage(Event $event)
    {
        if (self::$_seoPage) {
            self::$_seoPage->registerMeta($this->owner);
        }
    }

}