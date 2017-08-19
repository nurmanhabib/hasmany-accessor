<?php

namespace Nurmanhabib\HasManyAccessor;

trait HasManyAccessorTrait
{
    public function getAttribute($key)
    {
        $methodAccessor = 'get' . studly_case($key) . 'Accessor';

        if (method_exists($this, $methodAccessor)) {
            return call_user_func([$this, $methodAccessor]);
        }

        return parent::getAttribute($key);
    }

    public function accessorTo($relatedName, $groupBy = 'name')
    {
        return new AccessorTo($this, $relatedName, $groupBy);
    }
}
