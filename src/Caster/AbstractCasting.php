<?php

declare(strict_types=1);

namespace Php\Support\Laravel\Caster;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Php\Support\Exceptions\Exception;
use Php\Support\Exceptions\JsonException;
use Php\Support\Helpers\Json;
use Php\Support\Traits\ConfigurableTrait;

abstract class AbstractCasting implements Caster, Jsonable, Arrayable
{
    use ConfigurableTrait;

    /**
     * AbstractCasting constructor.
     *
     * @param string|array|static $value
     */
    public function __construct($value = null)
    {
        $this->fill($value);
    }

    /**
     * Fill the instance with data
     *
     * @param string|array|static $value
     *
     * @return AbstractCasting
     * @throws Exception
     * @throws JsonException
     */
    public function fill($value): self
    {
        if (empty($value)) {
            return $this;
        }

        if (is_string($value)) {
            $value = static::dataFromJson($value);
        }

        if ($value instanceof static) {
            $value = $value->toArray();
        }

        if (!is_array($value)) {
            throw new Exception('type of value must be Array');
        }

        return $this->configurable($value);
    }

    /**
     * JSON-string to Array
     *
     * @param string|null|array $json
     *
     * @return array
     * @throws JsonException
     */
    protected static function dataFromJson($json): array
    {
        if (empty($json)) {
            return null;
        }

        if (is_array($json)) {
            return $json;
        }

        return Json::decode($json);
    }

    /**
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * @param static|array $value
     *
     * @return string|null
     * @throws JsonException
     */
    public static function castToDatabase($value): ?string
    {
        if ($value instanceof static) {
            return $value->toJson();
        }

        return static::dataToJson($value);
    }

    /**
     * @param int $options
     *
     * @return string|null
     * @throws JsonException
     */
    public function toJson($options = 320): ?string
    {
        return static::dataToJson($this->toArray(), $options);
    }

    /**
     * Array to JSON-string
     *
     * @param array $data
     * @param int $options Default is `JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE`.
     *
     * @return string
     * @throws JsonException
     */
    protected static function dataToJson(array $data, $options = 320): ?string
    {
        return Json::encode($data, $options);
    }

    /**
     * @param string|null $value
     *
     * @return $this
     * @throws Exception
     * @throws JsonException
     */
    public function castFromDatabase(?string $value)
    {
        return $this->fill($value);
    }
}