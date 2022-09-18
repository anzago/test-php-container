<?php

namespace Test\Container;

use Psr\Container\NotFoundExceptionInterface;

class ResourceNotFoundException extends \Exception implements NotFoundExceptionInterface
{

}