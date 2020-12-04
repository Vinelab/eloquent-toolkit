<?php

namespace Amerald\Eloquent\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait GeneratesUUID
 *
 * Automatically generates a UUID on Eloquent 'creating' event.
 *
 * Note: in order to use UUID as the primary key, the following needs to be added to the model
 * ```
 *     public $incrementing = false;
 *     public $timestamps = false;
 * ```
 *
 * Otherwise, Eloquent will replace the UUID with an integer.
 */
trait GeneratesUUID
{
    /**
     * Define columns for which a UUID needs to be generated.
     *
     * @return string[]
     */
    protected function uuidColumns(): array
    {
        return ['id'];
    }

    protected static function bootGeneratesUUID()
    {
        static::creating(function (Model $model) {
            foreach ($model->uuidColumns() as $uuidColumn) {
                if (!$model->{$uuidColumn}) {
                    $model->{$uuidColumn} = (string) Str::uuid();
                }
            }
        });
    }
}
