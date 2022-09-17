<?php

namespace Test\Container\Test;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Test\Container\Container;
use Test\Container\Test\Fixtures\Bar;
use Test\Container\Test\Fixtures\Foo;

class ContainerTest extends TestCase
{
    public function testGet_whenMissingFactory_shouldThrowException()
    {
        $container = new Container();

        $this->expectException(NotFoundExceptionInterface::class);

        $container->get('Foo');
    }

    public function testGet_whenOneLevelDependency_shouldExecuteFactory()
    {
        $container = new Container();
        $container->set('Foo', fn (ContainerInterface $c) => new Foo());

        $foo = $container->get('Foo');

        $this->assertInstanceOf(Foo::class, $foo);
    }

    public function testGet_whenTwoLevelDependency_shouldExecuteFactories()
    {
        $container = new Container();
        $container->set('Foo', fn (ContainerInterface $c) => new Foo());
        $container->set('Bar', function (ContainerInterface $c) {
            $foo = $c->get('Foo');
            return new Bar($foo);
        });

        $bar = $container->get('Bar');

        $this->assertInstanceOf(Bar::class, $bar);
    }

    public function testHas_whenExistingFactory_shouldReturnTrue()
    {
        $container = new Container();
        $container->set('Foo', fn (ContainerInterface $c) => new Foo());

        $hasFoo = $container->has('Foo');

        $this->assertTrue($hasFoo);
    }

    public function testHas_whenNonExistingFactory_shouldReturnFalse()
    {
        $container = new Container();

        $hasFoo = $container->has('Foo');

        $this->assertFalse($hasFoo);
    }
}