<?php

namespace DKulyk\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;

/**
 * Class Logging.
 *
 * @mixin Eloquent
 *
 * @property Logging\Model[]|Collection $logs
 */
trait Logging
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logs()
    {
        return $this->morphMany(Logging\Model::class, 'object');
    }

    /**
     * The "booting" method of the trait.
     */
    public static function bootLogging()
    {
        static::observe(Logging\Listener::class);
    }
}
