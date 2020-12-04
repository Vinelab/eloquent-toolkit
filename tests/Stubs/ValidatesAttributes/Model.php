<?php

namespace Amerald\Eloquent\Tests\Stubs\ValidatesAttributes;

use Amerald\Eloquent\Traits\ValidatesAttributes;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use ValidatesAttributes;

    protected $table = 'validates_attributes_stubs';

    protected $fillable = [
        'name',
        'email',
        'address',
    ];

    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'nullable|string',
        ];
    }
}
