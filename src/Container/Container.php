<?php

namespace Test\Container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $factories = [];

    public function set(string $id, callable $factory)
    {
        $this->factories[$id] = $factory;
    }

    public function get(string $id)
    {
        if (!isset($this->factories[$id])) {
            throw new FactoryNotFoundException();
        }

        return $this->factories[$id]($this);
    }

    public function has(string $id): bool
    {
        return isset($this->factories[$id]);
    }
}