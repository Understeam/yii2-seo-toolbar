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
     * @var array the list of IPs that are allowed to access this module.
     * Each array element represents a single IP filter which can be either an IP address
     * or an address with wildcard (e.g. 192.168.0.*) to represent a network segment.
     * The default value is `['127.0.0.1', '::1']`, which means the module can only be accessed
     * by localhost.
     */
    public $allowedIPs = ['127.0.0.1', '::1'];
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
        if ($this->permission == '@') {
            return !Yii::$app->user->isGuest;
        }
        if ($this->permission !== null) {
            return $app->user->can($this->permission, ['module' => $this]);
        }
        $ip = Yii::$app->getRequest()->getUserIP();
        foreach ($this->allowedIPs as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
                return true;
            }
        }
        Yii::warning('Access to Gii is denied due to IP address restriction. The requested IP is ' . $ip, __METHOD__);

        return false;
    }

}