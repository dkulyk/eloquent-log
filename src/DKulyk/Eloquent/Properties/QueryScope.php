<?php

namespace DKulyk\Eloquent\Properties;

use DKulyk\Eloquent\Properties\Relations\Values;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;

class QueryScope implements Scope
{
    /**
     * @param Builder  $builder
     * @param Eloquent $model
     *
     * @throws \InvalidArgumentException
     */
    public function apply(Builder $builder, Eloquent $model)
    {
        $query = $builder->getQuery();
        if ((count($loads = $builder->getEagerLoads()) === 0 && count($query->wheres) === 0)
            || count($properties = Factory::getPropertiesByEntity($model)) === 0
        ) {
            return;
        }

        //compact eager loading
        if (count($loads) > 0) {
            $eadgeLoads = [];
            $props = [];
            foreach ($loads as $load => $data) {
                if ($properties->has($load)) {
                    $props[$load] = $properties->get($load);
                    //$eadgeLoads[$load] = $data;
                } else {
                    $eadgeLoads[$load] = $data;
                }
            }
            if (count($props)) {
                $eadgeLoads = [
                        'values' => function (Values $relation) use ($props) {
                            $relation->setProperties(new Collection($props));
                        },
                    ] + $eadgeLoads;
            }
            $builder->setEagerLoads($eadgeLoads);
        }
        $table = $model->getTable();
        $columns = $this->parseWhere($properties, $query->wheres, $table);
        if (count($columns) === 0) {
            return;
        }

        $value = new Value();
        $query->select($table.'.*');
        $multiple = false;
        foreach ($columns as $alias => $property) {
            if ($property->multiple) {
                $multiple = true;
            }
            $query->leftJoin(
                $value->getTable().' AS '.$alias,
                function (JoinClause $join) use ($model, $alias, $property) {
                    $join->on($model->getTable().'.'.$model->getKeyName(), '=', $alias.'.entity_id');
                    $join->where($alias.'.property_id', '=', $property->id);
                }
            );
        }

        if ($multiple) {
            //Distinct if condition by multiple values
            $query->distinct();
        }
    }

    /**
     * @param Collection $properties
     * @param            $wheres
     * @param string     $table
     *
     * @return array
     */
    protected function parseWhere($properties, &$wheres, $table)
    {
        static $i;
        $fields = [];
        if (is_array($wheres)) {
            foreach ($wheres as &$where) {
                //todo inverse if
                if (in_array($where['type'], ['Basic', 'Null', 'In'], true)) {
                    $column = str_replace($table.'.', '', $where['column']);
                    $property = $properties->get($column);
                    if ($property !== null) {
                        $alias = 'eav_'.$column.'_'.++$i;
                        $fields[$alias] = $property;
                        $where['column'] = $alias.'.value';
                    }
                } else {
                    $fields = array_merge($fields, $this->parseWhere($properties, $where['query']->wheres, $table));
                }
            }
        }

        return $fields;
    }
}
