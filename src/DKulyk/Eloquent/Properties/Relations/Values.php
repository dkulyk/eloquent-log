<?php

namespace DKulyk\Eloquent\Properties\Relations;

use DKulyk\Eloquent\Properties;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Support\Collection as BaseCollection;

class Values extends HasOneOrMany
{
    /**
     * @var BaseCollection|Properties\Property[]
     */
    protected $properties;

    /**
     * Values constructor.
     *
     * @param Builder        $query
     * @param Eloquent       $parent
     * @param string         $foreignKey
     * @param string         $localKey
     * @param BaseCollection $properties
     */
    public function __construct(Builder $query, Eloquent $parent, $foreignKey, $localKey, BaseCollection $properties = null)
    {
        $this->setProperties($properties);
        parent::__construct($query, $parent, $foreignKey, $localKey);
    }

    /**
     * @return Properties\Property[]|BaseCollection
     */
    protected function getProperties()
    {
        return $this->properties ?: $this->getParent()->getPropertyFactory()->getProperties();
    }

    public function setProperties(BaseCollection $properties = null)
    {
        $this->properties = $properties === null ? null : $properties->keyBy('name');
    }

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        $this->addPropertyConstraints();

        return $this->query->get();
    }

    /**
     * Get the relationship for eager loading.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEager()
    {
        $this->addPropertyConstraints();

        return $this->query->get();
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param array  $models
     * @param string $relation
     *
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        return $models;
    }

    /**
     * Get the parent model of the relation.
     *
     * @return \Illuminate\Database\Eloquent\Model|Properties
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the property constraints on the relation query.
     */
    public function addPropertyConstraints()
    {
        $properties = $this->getProperties();
        if ($properties->count()) {
            $this->query->getQuery()
                ->whereIn('property_id', $properties->pluck('id'));
        } else {
            $this->query->getQuery()->whereIn('property_id', []);
        }
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param Eloquent[]|Properties[] $models
     * @param Collection              $results
     * @param string                  $relation
     *
     * @return array
     */
    public function match(array $models, Collection $results, $relation)
    {
        if ($results->count() === 0) {
            return $models;
        }

        $dictionary = $this->buildDictionary($results);
        $properties = $this->getProperties();

        // Once we have the dictionary we can simply spin through the parent models to
        // link them up with their children using the keyed dictionary to make the
        // matching very convenient and easy work. Then we'll just return them.
        foreach ($models as $model) {
            $key = $model->getAttribute($this->localKey);

            if (array_key_exists($key, $dictionary)) {
                $value = $this->getRelationValue($dictionary, $key, 'many');

                $model->getPropertyFactory()->setPropertyValues($properties, $value);
            } else {
                $model->getPropertyFactory()->setPropertyValues($properties, null);
            }
        }

        return $models;
    }
}
