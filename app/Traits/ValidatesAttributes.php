<?php

namespace Amerald\Eloquent\Traits;

use Amerald\Eloquent\Exceptions\AttributeValidationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait ValidatesAttributes
{
    /**
     * @var bool $shouldValidateAtRuntime
     */
    protected static $shouldValidateAtRuntime = true;

    /**
     * Enable validation of attributes as soon as they are set/retrieved.
     *
     * $model->name = 'John Doe'
     * dump($model->name)
     */
    public static function enableRuntimeValidation(): void
    {
        static::$shouldValidateAtRuntime = true;
    }

    /**
     * Disable validation of attributes upon setting/getting.
     */
    public static function disableRuntimeValidation(): void
    {
        static::$shouldValidateAtRuntime = false;
    }

    /**
     * Validate attributes on save.
     */
    protected static function bootValidatesAttributes()
    {
        static::saving(function ($model) {
            $model->validate();
        });
    }

    /**
     * Define the validation rules.
     *
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Validate attributes.
     */
    public function validate()
    {
        $validator = Validator::make($this->attributesToArray(), $this->rules());

        try {
            $validator->validate();
        } catch (ValidationException $e) {
            $e = new AttributeValidationException($this->errorsToString($validator));
            $e->setValidator($validator);

            throw $e;
        }
    }

    public function setAttribute($key, $value)
    {
        $attribute = parent::setAttribute($key, $value);

        if (!static::$shouldValidateAtRuntime) {
            return $attribute;
        }

        // Get rule for current attribute
        $rule = Arr::get($this->rules(), $key);

        if (!$rule) {
            return $attribute;
        }

        $rule = [$key => $rule];

        $validator = Validator::make([$key => $this->attributes[$key]], $rule);

        // Validate attribute
        try {
            $validator->validate()[$key];
        } catch (ValidationException $exception) {
            unset($this->attributes[$key]);

            $e = new AttributeValidationException($this->errorsToString($validator));
            $e->setValidator($validator);

            throw $e;
        }

        return $this;
    }

    public function getAttribute($key)
    {
        $attribute = parent::getAttribute($key);

        if (!static::$shouldValidateAtRuntime) {
            return $attribute;
        }

        // Get rule for current attribute
        $rule = Arr::get($this->rules(), $key);

        if (!$rule) {
            return $attribute;
        }

        $rule = [$key => $rule];

        $validator = Validator::make([$key => $this->attributes[$key] ?? null], $rule);

        // Validate attribute
        try {
            $validator->validate()[$key];
        } catch (ValidationException $exception) {
            unset($this->attributes[$key]);

            $e = new AttributeValidationException($this->errorsToString($validator));
            $e->setValidator($validator);

            throw $e;
        }

        return $attribute;
    }

    private function errorsToString(\Illuminate\Contracts\Validation\Validator $validator): string
    {
        return "\n" . implode("\n", $validator->errors()->all());
    }
}
