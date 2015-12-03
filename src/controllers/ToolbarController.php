<?php

namespace understeam\seotoolbar\controllers;

use understeam\seotoolbar\models\PageForm;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class ToolbarController TODO: WRITE CLASS DESCRIPTION
 * @author Anatoly Rugalev <rugalev@enaza.ru>
 */
class ToolbarController extends Controller
{

    public $layout = false;

    /** @var \understeam\seotoolbar\Module */
    public $module;

    public function beforeAction($action)
    {
        if (!$this->module->checkAccess(Yii::$app)) {
            throw new ForbiddenHttpException();
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $url = preg_replace('#^' . Yii::$app->request->hostInfo . '#', '', Yii::$app->request->referrer);
        $model = PageForm::createByUrl($url);
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('ajax') == 'seo-entity-form') {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->apply()) {
                Yii::$app->session->addFlash('seo-success', 'Seo entity successfully saved!');
            }
        }
        $seoAttributes = Yii::$app->session->get('seoAttributes:' . $url, []);
        return $this->renderAjax($this->action->id, compact('model', 'seoAttributes'));
    }

}