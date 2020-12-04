<?php

namespace Amerald\Eloquent\Tests;

use Amerald\Eloquent\Tests\Stubs\GeneratesUUID\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GeneratesUUIDTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('generates_uuid_stubs', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name')->nullable();
            $table->string('custom_column');
        });
    }

    public function testShouldGenerateUUIDOnCreate()
    {
        $model = new Model();
        $this->assertNull($model->id);

        $model->save();

        $this->assertMatchesRegularExpression(
            '/\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b/',
            $model->first()->id
        );
    }

    public function testShouldNotGenerateUUIDOnUpdate()
    {
        $model = new Model();
        $model->save();
        $uuid = $model->first()->id;

        $model->update([
            'name' => 'John Doe',
        ]);

        $this->assertEquals($uuid, $model->fresh()->id);
    }

    public function testShouldGenerateUUIDForUserDefinedAttributes()
    {
        $model = new Model();
        $model->save();

        $model = $model->first();

        $this->assertMatchesRegularExpression(
            '/\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b/',
            $model->id
        );

        $this->assertMatchesRegularExpression(
            '/\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b/',
            $model->custom_column
        );
    }
}
