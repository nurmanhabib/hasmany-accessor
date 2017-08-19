<?php

namespace Nurmanhabib\HasManyAccessor;

use Illuminate\Database\Eloquent\Model;

class AccessorTo
{
    protected $model;
    protected $relatedName;
    protected $groupBy;
    protected $results;

    public function __construct(Model $model, $relatedName, $groupBy = 'name')
    {
        $this->model = $model;
        $this->relatedName = $relatedName;
        $this->groupBy = $groupBy;
        $this->results = [];
    }

    public function has($name)
    {
        return array_key_exists($name, $this->addresses);
    }

    public function get($name)
    {
        if ($result = array_get($this->results, $name)) {
            return $result;
        }

        return $this->results[$name] = $this->getFromQuery($name);
    }

    protected function getFromQuery($name)
    {
        return $this->makeRelatedQuery()->firstOrNew([$this->groupBy => $name]);
    }

    protected function makeRelatedQuery()
    {
        return call_user_func([$this->model, $this->relatedName]);
    }

    public function set(Model $model, $name)
    {
        $relatedClass = get_class($this->makeRelatedQuery()->getModel());

        if (get_class($model) != $relatedClass) {
            throw new \Exception('Value must be instance of ' . $relatedClass);
        }

        $related = $this->get($name);
        $related->fill($model->toArray());

        return $related;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($value, $name);
    }
}
