<?php

namespace DKulyk\Eloquent\Properties;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Config;

/**
 * Class Value.
 *
 * @property int      $entity_id
 * @property int      $property_id
 * @property string   $value
 * @property Property $property
 */
class Value extends Eloquent
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'property_values';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['entity_id', 'property_id', 'value'];

    /**
     * Create a new Value model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('eloquent-extra.values_table', $this->table);
        parent::__construct($attributes);
    }

    /**
     * Create a new model instance that is existing.
     *
     * @param array       $attributes
     * @param string|null $connection
     *
     * @return static
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        /* @var Value $instance */
        $property = Factory::getPropertyById($attributes->property_id);
        $instance = Factory::getType($property->type) ?: $this;

        $model = $instance->newInstance([], true);
        $model->setRelation('property', $property);
        $model->setRawAttributes((array) $attributes, true);
        $model->setConnection($connection ?: $this->connection);

        return $model;
    }

    /**
     * Set the keys for a save update query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query->where(
            [
                'entity_id'   => $this->entity_id,
                'property_id' => $this->property_id,
            ]
        );

        return $query;
    }

    /**
     * Get property relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
