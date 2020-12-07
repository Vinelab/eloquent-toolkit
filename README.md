# Eloquent Toolkit
Enjoy Eloquent attributes validation and other cool features.

## Installation
`composer require amerald/eloquent-toolkit`

## Usage
### Attributes Validation
Laravel's validation is amazing. 
Though, it is not always the case that the validated data will get unchanged to an Eloquent model: 
we may need to transform it in one way or another, or even compose a model from several data sources.

Wouldn't it be great if we could validate model attributes in a similar way we do with function arguments?
Specify whether an attribute is required along with its type(s)?

```php
<?php

use Illuminate\Database\Eloquent\Model;
use Amerald\Eloquent\Traits\ValidatesAttributes;

class Post extends Model
{
    use ValidatesAttributes;
    
    protected function rules(): array
    {
        return [
            'title' => 'required|string',
            'body' => 'required|string',
            'image' => 'nullable|string',
        ];
    }
}
```

There are a few validation options. In each case an `Amerald\Eloquent\Exceptions\AttributeValidationException` will be thrown on validation failure:
```
Amerald\Eloquent\Exceptions\AttributeValidationException: 
The title field is required.
The body field is required.
The image must be a string.
```

#### Automatic Validation
By default automation is performed on `saving` event

#### Runtime Validation
When validation at runtime is enabled, an attribute is validated immediately upon setting/getting.
Each time a new validator instance is constructed.

Runtime validation is enabled by default. It can be disabled by calling a static `disableRuntimeValidation()` method.

```php
$post = new Post();
$post->title = false; // trying to set an invalid type
dump($post->body); // trying to access a required attribute that currently is null
```

#### Manual Validation
```php
Post::disableRuntimeValidation();

$post = Post::make([
    'title' => null,
    'body' => null,
    'image' => null,
]);

$post->validate();
```

### Generating UUIDs
Say, we need to generate a UUID for our primary key field `id`:

```php
<?php

use Illuminate\Database\Eloquent\Model;
use Amerald\Eloquent\Traits\GeneratesUUID;

class Post extends Model
{
    use GeneratesUUID;
    
    public $incrementing = false;
    
    public $timestamps = false;
}
```

A UUID will be generated **once** on 'creating' event. 

It is possible to generate a UUID for multiple columns:

```php

class Post extends Model
{
    use GeneratesUUID;
    
    public $incrementing = false;
        
    public $timestamps = false;
    
    protected function uuidColumns(): array
    {
        return [
            'id',
            'custom_column'
        ];
    }
}
```
