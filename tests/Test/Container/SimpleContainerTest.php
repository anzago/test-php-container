<?php

namespace Test\Container\Test;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Test\Container\SimpleContainer;
use Test\Container\Test\Fixtures\Bar;
use Test\Container\Test\Fixtures\Foo;

class SimpleContainerTest extends TestCase
{
    public function testGet_whenMissingFactory_shouldThrowException()
    {
        $container = new SimpleContainer();

        $this->expectException(NotFoundExceptionInterface::class);

        $container->get('Foo');
    }

    public function testGet_whenOneLevelDependency_shouldExecuteFactory()
    {
        $container = new SimpleContainer();
        $container->set('Foo', fn (ContainerInterface $c) => new Foo());

        $foo = $container->get('Foo');

        $this->assertInstanceOf(Foo::class, $foo);
    }

    public function testGet_whenTwoLevelDependency_shouldExecuteFactories()
    {
        $container = new SimpleContainer();
        $container->set('Foo', fn (ContainerInterface $c) => new Foo());
        $container->set('Bar', function (ContainerInterface $c) {
            $foo = $c->get('Foo');
            return new Bar($foo);
        });

        $bar = $container->get('Bar');

        $this->assertInstanceOf(Bar::class, $bar);
    }

    public function testGet_whenPassingSameId_shouldReturnSameInstance()
    {
        $container = new SimpleContainer();
        $container->set('Foo', fn (ContainerInterface $c) => new Foo());

        $foo1 = $container->get('Foo');
        $foo2 = $container->get('Foo');

        $this->assertSame($foo1, $foo2);
    }

    public function testHas_whenExistingFactory_shouldReturnTrue()
    {
        $container = new SimpleContainer();
        $container->set('Foo', fn (ContainerInterface $c) => new Foo());

        $hasFoo = $container->has('Foo');

        $this->assertTrue($hasFoo);
    }

    public function testHas_whenNonExistingFactory_shouldReturnFalse()
    {
        $container = new SimpleContainer();

        $hasFoo = $container->has('Foo');

        $this->assertFalse($hasFoo);
    }
}