<?php

namespace understeam\seotoolbar;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\Application;
use yii\web\User;

/**
 * Class Module TODO: WRITE CLASS DESCRIPTION
 * @author Anatoly Rugalev <rugalev@enaza.ru>
 */
class Module extends \yii\base\Module implements BootstrapInterface
{

    public $controllerNamespace = 'understeam\seotoolbar\controllers';

    public $permission = 'seo';

    public $logCategory = 'seo';

    public $behaviorConfig = 'understeam\seotoolbar\behaviors\ViewBehavior';

    public $toolbarBehaviorConfig = 'understeam\seotoolbar\behaviors\ToolbarBehavior';

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if (!$app instanceof Application) {
            return;
        }
        if (!$app->user instanceof User) {
            return;
        }
        Yii::info("Loaded SEO Toolbar module", $this->logCategory);
        Yii::$app->view->attachBehavior('seoView', $this->behaviorConfig);
        if ($this->checkAccess($app)) {
            $app->getUrlManager()->addRules([
                'seoToolbar/toolbar' => 'seoToolbar/toolbar/index'
            ]);
            if (!$app->request->isAjax) {
                Yii::$app->view->attachBehavior('seoToolbar', $this->toolbarBehaviorConfig);
            }
        }
    }

    public function checkAccess($app)
    {
        return $app->user->can($this->permission, ['module' => $this]);
    }

}