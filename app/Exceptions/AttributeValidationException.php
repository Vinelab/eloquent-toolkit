<?php

namespace Amerald\Eloquent\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use RuntimeException;

class AttributeValidationException extends RuntimeException
{
    /**
     * @var Validator $validator
     */
    private $validator;

    /**
     * @param  Validator  $validator
     * @return $this
     */
    public function setValidator(Validator $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * @return Validator
     */
    public function getValidator(): Validator
    {
        return $this->validator;
    }
}
