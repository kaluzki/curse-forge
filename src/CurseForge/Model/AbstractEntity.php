<?php

namespace kaluzki\CurseForge\Model;

use Phpio\Spl\WithPropertiesTrait;

/**
 * This class uses closures for lazy loading of properties
 */
abstract class AbstractEntity implements \JsonSerializable
{
    use WithPropertiesTrait {
        __get as private _getProperty;
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        $value = $this->_getProperty($name);
        if ($value instanceof \Closure) {
            $this->__set($name, $value = call_user_func($value, $this, $name));
        }
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        $properties = $this->properties;
        array_walk($properties, function(&$property, $name) {
            $property = $this->$name;
        });
        return $properties;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return (string) json_encode($this, JSON_PRETTY_PRINT);
    }
}