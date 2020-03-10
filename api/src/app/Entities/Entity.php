<?php

namespace App\Entities;

use Carbon\Carbon;

/**
 * Class Entity
 * @package App\Entities
 */
abstract class Entity implements EntityInterface
{
    /**
     * Convert object to array
     *
     * @return array
     */
    public function __toArray(): array
    {
        return call_user_func('get_object_vars', $this);
    }

    /**
     * @param $class
     * @param array $data
     * @return mixed
     */
    public static function toObject($class, array $data)
    {
        $object = new $class();
        foreach ($data as $key => $value) {
            if (property_exists($class, $key)) {
                $object->{'set' . ucfirst($key)}($value);
            }
        }
        return $object;
    }

    /**
     * Convert string date time to int timestamp
     *
     * @param string $date
     * @return int
     */
    protected function dateToTimestamp(string $date): int
    {
        return Carbon::parse($date)->getTimestamp();
    }
}
