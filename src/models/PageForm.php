<?php

namespace understeam\seotoolbar\models;

use Yii;
use yii\base\Model;

/**
 * Class PageForm TODO: WRITE CLASS DESCRIPTION
 * @author Anatoly Rugalev <rugalev@enaza.ru>
 */
class PageForm extends Model
{

    public $pattern;
    public $title;
    public $keywords;
    public $description;

    public $isNewRecord = false;

    /** @var Page */
    private $_page;

    public function rules()
    {
        return [
            ['pattern', 'required'],
            ['title', 'string'],
            ['keywords', 'string'],
            ['description', 'string'],
        ];
    }


    public static function createByUrl($url)
    {
        $model = new self;
        $url = preg_replace('#^' . Yii::$app->request->hostInfo . '#', '', $url);
        $model->_page = Page::findByUrl($url);
        if (!$model->_page) {
            $model->_page = new Page([
                'pattern' => $url,
                'isNewRecord' => true,
            ]);
        }
        $model->pattern = $model->_page->pattern;
        $model->title = $model->_page->title;
        $model->keywords = $model->_page->keywords;
        $model->description = $model->_page->description;
        return $model;
    }

    public function apply()
    {
        $this->_page->setAttributes($this->getAttributes([
            'pattern',
            'title',
            'keywords',
            'description',
        ]), false);
        return $this->_page->save(false);
    }

}