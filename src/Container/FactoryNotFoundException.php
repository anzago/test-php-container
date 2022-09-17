<?php

namespace Test\Container;

use Psr\Container\NotFoundExceptionInterface;

class FactoryNotFoundException extends \Exception implements NotFoundExceptionInterface
{

}