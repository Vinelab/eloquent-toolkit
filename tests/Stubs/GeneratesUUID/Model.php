<?php

namespace Amerald\Eloquent\Tests\Stubs\GeneratesUUID;

use Amerald\Eloquent\Traits\GeneratesUUID;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use GeneratesUUID;

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'generates_uuid_stubs';

    protected $fillable = [
        'name',
    ];

    protected function uuidColumns(): array
    {
        return [
            'id',
            'custom_column',
        ];
    }
}
