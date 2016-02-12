<?php

namespace DKulyk\Eloquent\Properties;

use DKulyk\Eloquent\Properties;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Config;

/**
 * Class Property.
 *
 * @property int    $id
 * @property string $entity
 * @property string $name
 * @property string $type
 * @property bool   $multiple
 * @property mixed  $default_value
 * @property string $reference
 */
class Property extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'properties';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts
        = [
            'meta'       => 'array',
            'multivalue' => 'boolean',
        ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'entity',
            'name',
            'type',
            'multiple',
            'default',
            'meta',
        ];

    /**
     * Create a new Property model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('eloquent-extra.properties_table', $this->table);
        parent::__construct($attributes);
    }
}
