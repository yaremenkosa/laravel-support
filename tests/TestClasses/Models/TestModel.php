<?php

namespace Php\Support\Laravel\Tests\TestClasses\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;
use Php\Support\Laravel\Caster\HasCasts;
use Php\Support\Laravel\Tests\TestClasses\Entity\Params;
use Php\Support\Laravel\Tests\TestClasses\Entity\Status;

/**
 * Class TestModel
 * @package Php\Support\Laravel\Tests\Models
 * @property boolean $enabled
 * @property string $title
 * @property Params $params
 * @property array $config
 * @property string $str
 * @property string $str_empty
 * @property int $int
 * @property User $user
 * @mixin Builder
 */
class TestModel extends Model
{
    use HasCasts;

    public $timestamps = false;
    protected $keyType = 'uuid';
    protected $table   = 'test_table';

    protected $fillable = [
        'params',
        'config',
        'str',
        'str_empty',
        'int',
    ];

    protected $casts = [
        'enabled'   => 'bool',
        'params'    => Params::class,
        'status'    => Status::class,
        'config'    => 'array',
        'str'       => 'string',
        'str_empty' => null,
        'int'       => 'int',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
