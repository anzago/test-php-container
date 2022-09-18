<?php

namespace Test\Container;

use Psr\Container\ContainerInterface;

class SimpleContainer implements ContainerInterface
{
    private array $factories = [];

    private array $cache = [];

    public function set(string $id, callable $factory)
    {
        $this->factories[$id] = $factory;
    }

    public function get(string $id)
    {
        if (!isset($this->factories[$id])) {
            throw new FactoryNotFoundException();
        }

        if (!isset($this->cache[$id])) {
            $this->cache[$id] = $this->factories[$id]($this);
        }

        return $this->cache[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->factories[$id]);
    }
}