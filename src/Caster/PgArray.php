<?php

declare(strict_types=1);

namespace Php\Support\Laravel\Caster;

use Php\Support\Helpers\Arr;

class PgArray implements Caster
{
    public static function castToDatabase($value): ?string
    {
        return Arr::toPostgresArray(static::normalize($value));
    }

    protected static function normalize($value)
    {
        return array_filter($value);
    }

    public function castFromDatabase(?string $value)
    {
        return Arr::fromPostgresArray($value);
    }
}
