<?php

namespace understeam\seotoolbar\models;

use understeam\seotoolbar\behaviors\SeoEntity;
use Yii;
use yii\base\Model;
use yii\helpers\Json;
use yii\redis\ActiveRecord;
use yii\web\View;

/**
 * Class representing any page of website
 *
 * @property string $pattern
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $ogTags
 * @property int $created
 *
 * @author Anatoly Rugalev <rugalev@enaza.ru>
 */
class Page extends ActiveRecord
{

    private static $_patterns;
    private $_models = [];

    private $_replaceData;

    public static function keyPrefix()
    {
        return 'seo-page';
    }

    public static function primaryKey()
    {
        return ['pattern'];
    }

    public function attributes()
    {
        return [
            'pattern',
            'title',
            'description',
            'keywords',
            'ogTags',
            'created',
        ];
    }

    /**
     * @param string $url
     * @return Page|null
     */
    public static function findByUrl($url)
    {
        $page = self::findOne($url);
        if (!$page) {
            $page = self::findByPattern($url);
        }
        return $page;
    }

    public static function findByPattern($url)
    {
        $patterns = self::getPatterns();
        foreach ($patterns as $pattern => $regexp) {
            if (preg_match($regexp, $url)) {
                return self::findOne($pattern);
            }
        }
        return null;
    }

    public static function getPatterns($refresh = false)
    {
        if (!isset(self::$_patterns) || $refresh) {
            if (!$refresh) {
                self::$_patterns = Yii::$app->cache->get('seoPatterns');
            }
            if (!self::$_patterns) {
                self::$_patterns = [];
                // TODO: Optimization
                /** @var Page[] $pages */
                $patterns = Page::find()->column('pattern');
                foreach ($patterns as $pattern) {
                    if (strpos($pattern, '*') !== false) {
                        self::$_patterns[$pattern] = '#^' . strtr($pattern, ['*' => '(.*)']) . '$#';
                    }
                }
                Yii::$app->cache->set('seoPatterns', self::$_patterns);
            }
        }
        return self::$_patterns;
    }

    public function addModel(Model $model, $key = null)
    {
        if ($key === null) {
            $this->_models[] = $model;
        } else {
            $this->_models[$key] = $model;
        }
    }

    public function registerMeta(View $view)
    {
        $view->title = $this->renderAttribute($this->title);
        $view->registerMetaTag([
            'name' => 'description',
            'content' => $this->renderAttribute($this->description),
        ]);
        $view->registerMetaTag([
            'name' => 'keywords',
            'content' => $this->renderAttribute($this->keywords),
        ]);
        foreach ($this->getOgTags() as $property => $template) {
            $view->registerMetaTag([
                'property' => $property,
                'value' => $this->renderAttribute($template),
            ]);
        }
    }

    public function renderAttribute($template)
    {
        return strtr($template, $this->getReplaceData());
    }

    public function getOgTags()
    {
        $result = Json::decode($this->ogTags);
        if (!is_array($result)) {
            $result = [];
        }
        return $result;
    }

    public function getReplaceData()
    {
        if (!isset($this->_replaceData)) {
            $data = [];
            /** @var Model|SeoEntity $model */
            foreach ($this->_models as $model) {
                $prefix = $model->getSeoPrefix();
                $attributeNames = $model->getSeoAttributes();
                $attributes = $model->getAttributes($attributeNames);
                foreach ($attributes as $name => $value) {
                    $data["{{$prefix}.{$name}}"] = $value;
                }
            }
            $this->_replaceData = $data;
        }
        return $this->_replaceData;
    }

}
