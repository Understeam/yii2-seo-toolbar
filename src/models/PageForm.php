<?php

namespace understeam\seotoolbar\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;

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
    public $ogTags = [];

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
            ['ogTags', 'validateOgTags'],
        ];
    }

    public function validateOgTags()
    {
        if (!is_array($this->ogTags)) {
            $this->ogTags = [];
        }
        foreach ($this->ogTags as $i => $tag) {
            if (!isset($tag['property']) || !$tag['property']) {
                unset($this->ogTags[$i]);
                continue;
            }
        }
    }

    public static function createByUrl($url)
    {
        $model = new self;
        $model->_page = Page::findByUrl($url);
        if (!$model->_page) {
            $model->_page = new Page([
                'pattern' => $url,
            ]);
            $model->isNewRecord = true;
        }
        $model->ogTags = Json::decode($model->_page->ogTags);
        if (!is_array($model->ogTags)) {
            $model->ogTags = [];
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
        $this->_page->ogTags = Json::encode($this->ogTags);
        $result = $this->_page->save(false);
        Page::getPatterns(true);
        return $result;
    }

    public function attributeLabels()
    {
        return [
            'pattern' => 'URL or pattern with star (*)',
            'title' => 'Page title',
            'keywords' => 'Keywords',
            'descrpition' => 'Descrpition',
            'ogTags' => 'Open Graph tags',
            'ogTags[property]' => 'property like og:title',
            'ogTags[content]' => 'content',
        ];
    }

}