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
        if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            return;
        }
        if (Yii::$app->controller->module->id == 'seoToolbar') {
            return;
        }
        $url = Yii::$app->request->url;
        if (!isset(self::$_seoPage)) {
            self::$_seoPage = Page::findByUrl($url);
        }
        if (self::$_seoPage) {
            $params = $event->params;
            foreach ($params as $key => $param) {
                if ($param instanceof Model && $param->hasMethod('getSeoPrefix')) {
                    self::$_seoPage->addModel($param, $key);
                }
            }
        }
        if (Yii::$app->getModule('seoToolbar')->checkAccess(Yii::$app)) {
            Yii::$app->session->set('seoAttributes:' . $url, self::$_seoPage->getReplaceData());
        }
    }

    public function beginPage(Event $event)
    {
        if (self::$_seoPage) {
            self::$_seoPage->registerMeta($this->owner);
        }
    }

}