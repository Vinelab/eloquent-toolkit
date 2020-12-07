<?php

namespace Amerald\Eloquent\Tests;

use Amerald\Eloquent\Exceptions\AttributeValidationException;
use Amerald\Eloquent\Tests\Stubs\ValidatesAttributes\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ValidatesAttributesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('validates_attributes_stubs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('address')->nullable();
            $table->string('attribute_without_rules');
        });
    }

    public function testShouldValidateOnSaving()
    {
        $this->expectExceptionObject(new AttributeValidationException(
            "The name field is required.
The email field is required."
        ));

        $model = new Model();
        $model->save();
    }

    public function testShouldValidateAtRuntime()
    {
        $model = new Model();
        $model::enableRuntimeValidation();

        $this->expectExceptionObject(new AttributeValidationException(
            "The name must be a string."
        ));

        $model->name = false; //set
        $model->name; // get
    }

    public function testShouldNotValidateAtRuntime()
    {
        $model = new Model();
        $model::disableRuntimeValidation();

        $model->name = false; // set
        $model->name; // get

        // If an exception is thrown, we won't be able to get here
        $this->assertTrue(true);
    }

    public function testShouldValidateWhenCalledManually()
    {
        $model = new Model();

        $this->expectExceptionObject(new AttributeValidationException(
            "The name field is required.
The email field is required."
        ));

        $model->validate();
    }

    public function testShouldValidateNullableAttributes()
    {
        $model = new Model([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'address' => null,
        ]);

        // Should not throw an exception
        $model->validate();

        $model->address = false;

        $this->expectException(AttributeValidationException::class);
        $this->expectExceptionMessage('The address must be a string.');

        $model->validate();
    }

    public function testShouldNotValidateAttributesWithoutRules()
    {
        Model::enableRuntimeValidation();
        $model = new Model();
        $model->attribute_without_rules = null; //set
        $model->attribute_without_rules; // get

        // If an exception is thrown, we won't be able to get here
        $this->assertTrue(true);
    }
}
