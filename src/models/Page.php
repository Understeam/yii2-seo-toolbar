<?php

namespace understeam\seotoolbar\models;

use understeam\seotoolbar\behaviors\SeoEntity;
use yii\base\Model;
use yii\redis\ActiveRecord;
use yii\web\View;

/**
 * Class representing any page of website
 *
 * @property string $pattern
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $meta
 * @property int $created
 *
 * @author Anatoly Rugalev <rugalev@enaza.ru>
 */
class Page extends ActiveRecord
{

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

    /**
     * @param string $url
     * @return Page|null
     */
    public static function findByUrl($url)
    {
        return self::findOne($url);
    }

    public function attributes()
    {
        return [
            'pattern',
            'title',
            'description',
            'keywords',
            'meta',
            'created',
        ];
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
        foreach ($this->getExtraMetaTags() as $name => $template) {
            $view->registerMetaTag([
                'name' => $name,
                'value' => $this->renderAttribute($template),
            ]);
        }
    }

    public function renderAttribute($template)
    {
        return strtr($template, $this->getReplaceData());
    }

    public function getExtraMetaTags()
    {
        return [];
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
