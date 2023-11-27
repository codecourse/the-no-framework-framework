<?php

namespace App\Models;

abstract class Model
{
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
    }

    public function __isset($name)
    {
        if (property_exists($this, $name)) {
            return true;
        }

        return false;
    }

    public function update(array $columns)
    {
        foreach ($columns as $column => $value) {
            $this->{$column} = $value;
        }
    }

    public function fill(array $columns)
    {
        $this->update($columns);
    }
}
