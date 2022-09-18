<?php

namespace Test\Container;

use Psr\Container\ContainerInterface;

class ArrayConfigurableContainer implements ContainerInterface
{
    private array $services = [];

    private array $servicesCache = [];

    private array $variables = [];

    public function configure(array $config): void
    {
        $this->validateConfiguration($config);

        if (isset($config['variables'])) {
            $this->variables = $config['variables'];
        }

        if (isset($config['services'])) {
            $this->services = $config['services'];
        }
    }

    private function validateConfiguration(array $config)
    {
        if (isset($config['services'])) {
            $this->validateServicesConfiguration($config['services']);
        }
    }

    private function validateServicesConfiguration(array $services)
    {
        foreach ($services as $serviceId => $serviceConfig) {
            if (!isset($serviceConfig['class'])) {
                throw new \InvalidArgumentException('"class" field is mandatory in service configuration. service name : ' . $serviceId);
            }
        }
    }

    public function get(string $id)
    {
        if (isset($this->variables[$id])) {
            return $this->variables[$id];
        }

        if (!isset($this->services[$id])) {
            throw new ResourceNotFoundException();
        }

        if (!isset($this->servicesCache[$id])) {
            $this->servicesCache[$id] = $this->instantiateService($this->services[$id]);
        }

        return $this->servicesCache[$id];
    }

    private function instantiateService($serviceConfig)
    {
        return new $serviceConfig['class'];
    }

    public function has(string $id): bool
    {
        // TODO: Implement has() method.
    }
}