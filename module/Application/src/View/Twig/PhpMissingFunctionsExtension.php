<?php

namespace Application\View\Twig;

use Twig\TwigFunction;
use Twig\TwigTest;
use ZendTwig\Extension\Extension as TwigExtension;

class PhpMissingFunctionsExtension extends TwigExtension
{
    public function getName()
    {
        return 'PhpMissingFunctionsExtension';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            'class' => new TwigFunction('class', [$this, 'getClass'])
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [
            new TwigTest('instanceof', [$this, 'isInstanceOf']),
        ];
    }

    public function isInstanceOf($object, $class)
    {
        $reflectionClass = new \ReflectionClass($class);
        return $reflectionClass->isInstance($object);
    }

    public function getClass($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }
}
