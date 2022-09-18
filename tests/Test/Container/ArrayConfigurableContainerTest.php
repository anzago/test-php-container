<?php

namespace Test\Container\Test;

use Psr\Container\NotFoundExceptionInterface;
use Test\Container\ArrayConfigurableContainer;
use PHPUnit\Framework\TestCase;
use Test\Container\Test\Fixtures\Foo;

class ArrayConfigurableContainerTest extends TestCase
{
    public function testGet_whenEmptyContainer_shouldThrowException()
    {
        $container = new ArrayConfigurableContainer();

        $this->expectException(NotFoundExceptionInterface::class);

        $container->get('Foo');
    }

    public function testGet_whenGettingVariable_shouldReturnVariable()
    {
        $container = new ArrayConfigurableContainer();
        $container->configure([
            'variables' => [
                'foo_var' => 'foo'
            ]
        ]);

        $variable = $container->get('foo_var');

        $this->assertEquals('foo', $variable);
    }

    public function testGet_whenGettingServiceWithNoParameter_shouldReturnService()
    {
        $container = new ArrayConfigurableContainer();
        $container->configure([
            'services' => [
                'Foo' => [
                    'class' => Foo::class
                ]
            ]
        ]);

        $foo = $container->get('Foo');

        $this->assertInstanceOf(Foo::class, $foo);
    }
}
