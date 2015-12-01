<?php

namespace understeam\seotoolbar\behaviors;

use yii\base\Behavior;

/**
 * Class SeoEntity TODO: WRITE CLASS DESCRIPTION
 * @author Anatoly Rugalev <rugalev@enaza.ru>
 */
class SeoEntity extends Behavior
{

    /**
     * @var \yii\base\Model
     */
    public $owner;

    public $prefix = null;
    public $attributes = [];

    public function getSeoPrefix()
    {
        if ($this->prefix === null) {
            $reflection = new \ReflectionClass($this->owner);
            $this->prefix = $reflection->getShortName();
        }
        return $this->prefix;
    }

    public function getSeoAttributes()
    {
        return $this->owner->attributes();
    }

}