<?php

namespace DKulyk\Eloquent\Logging;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;

/**
 * DtKt\Models\Log.
 *
 *
 * @property int        $type
 * @property string         $object_type
 * @property int        $object_id
 * @property \Carbon\Carbon $created_at
 * @property string         $data
 * @property int        $owner_id
 * @property-read Eloquent  $object
 */
class Model extends Eloquent
{
    const CREATE = 1;
    const UPDATE = 2;
    const DELETE = 3;
    const RESTORE = 4;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eloquent_log';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'owner_id',
            'type',
            'comment',
            'model',
            'object_id',
            'created_at',
            'data',
        ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts
        = [
            'type'       => 'integer',
            'data'       => 'array',
            'created_at' => 'datetime',
        ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = \Config::get('eloquent-extra.logging_table', $this->table);

        parent::__construct($attributes);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function object()
    {
        return $this->morphTo();
    }

    /**
     * Get restored model.
     *
     * @param bool $save
     *
     * @return Eloquent
     */
    public function restore($save = true)
    {
        $object = $this->object->fill($this->data);
        if ($save) {
            $object->save();
        }

        return $object;
    }

    /**
     * The "booting" method of the model.
     */
    public static function boot()
    {
        static::saving(
            function (Model $model) {
                $model->owner_id = Auth::id();
                $model->created_at = new Carbon();
            }
        );
        parent::boot();
    }
}
