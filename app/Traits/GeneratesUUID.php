<?php

namespace Amerald\Eloquent\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait GeneratesUUID
{
    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    /**
     * Define columns for which a UUID needs to be generated.
     *
     * @return string[]
     */
    public function uuidColumns(): array
    {
        return ['id'];
    }

    public static function generateUUID(Model $model, bool $override = false)
    {
        foreach ($model->uuidColumns() as $uuidColumn) {
            if ($override || !$model->{$uuidColumn}) {
                $model->{$uuidColumn} = (string) Str::uuid();
            }
        }
    }

    protected static function bootGeneratesUUID()
    {
        static::creating(function (Model $model) {
            static::generateUUID($model);
        });
    }
}
