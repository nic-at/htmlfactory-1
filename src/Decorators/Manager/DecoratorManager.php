<?php

namespace Webflorist\HtmlFactory\Decorators\Manager;

use Webflorist\HtmlFactory\Decorators\Abstracts\Decorator;
use Webflorist\HtmlFactory\Elements\Abstracts\Element;
use Webflorist\HtmlFactory\Exceptions\DecoratorNotFoundException;
use Webflorist\HtmlFactory\HtmlFactory;
use Webflorist\HtmlFactory\HtmlFactoryTools;

/**
 * This Class manages registration an application of Decorators.
 *
 * Class DecoratorManager
 * @package Webflorist\HtmlFactory\Decorators\Manager
 */
class DecoratorManager
{

    /**
     * Injected HtmlFactory service.
     *
     * @var HtmlFactory
     */
    protected $htmlFactory;

    /**
     * Array of registered decorators.
     *
     * @var string[]
     */
    protected $decorators = [];

    /**
     * A two-dimensional array, that lists all (parent-)classes of elements.
     * Gets populated at the start of decoration to determine the applicable decorators.
     *
     * @var string[][]
     */
    protected $elementClasses = [];

    /**
     * DecoratorManager constructor.
     * @param HtmlFactory $htmlFactory
     */
    public function __construct(HtmlFactory $htmlFactory)
    {
        $this->htmlFactory = $htmlFactory;
    }

    /**
     * Register decorators from the files of a directory.
     *
     * The classes of these files must extend 'Webflorist\HtmlFactory\Decorators\Abstracts\Decorator'.
     *
     * @param string $namespace
     * @param string $folder
     * @throws DecoratorNotFoundException
     */
    public function registerFromFolder(string $namespace, string $folder)
    {
        $decoratorFiles = scandir($folder);

        foreach ($decoratorFiles as $key => $decoratorFileName) {
            if (strpos($decoratorFileName, ".php") > 0) {
                $this->register($namespace . "\\" . substr($decoratorFileName, 0, -4));
            }
        }
    }

    /**
     * Registers a className as a decorator.
     *
     * The class must extend 'Webflorist\HtmlFactory\Decorators\Abstracts\Decorator'.
     *
     * @param string $className
     * @throws DecoratorNotFoundException
     */
    public function register(string $className)
    {
        if (!class_exists($className)) {
            throw new DecoratorNotFoundException('Decorator with class "' . $className . '" not found.');
        }

        if (!is_a($className, Decorator::class, true)) {
            throw new DecoratorNotFoundException('Class "' . $className . '" is not a decorator.');
        }

        /** @var Decorator $className */
        // We only register the decorator, if it's group-ID is stated under the config key 'htmlfactory.decorators'.
        if ($this->decoratorShouldBeRegistered($className)) {
            $this->decorators[$className] = $className;
        }
    }

    /**
     * Decorate an element.
     *
     * @param Element $element
     */
    public function decorate(Element $element)
    {
        foreach ($this->decorators as $decoratorClass) {
            if ($this->decoratorCanDecorateElement($decoratorClass, $element)) {
                /** @var Decorator $decorator */
                $decorator = new $decoratorClass($element);
                $decorator->decorate();
            }
        }
    }

    /**
     * Resolves all classes and parent classes of an element and stores them in $this->elementClasses.
     *
     * @param $elementClass
     */
    private function resolveElementClasses($elementClass)
    {
        if (!isset($this->elementClasses[$elementClass])) {
            $this->elementClasses[$elementClass] = HtmlFactoryTools::resolveObjectClasses($elementClass);
        }
    }

    /**
     * Checks if the decorator with class $decoratorClass can decorate $element.
     *
     * @param string $decoratorClass
     * @param Element $element
     * @return bool
     */
    private function decoratorCanDecorateElement($decoratorClass, Element $element)
    {
        $elementClass = get_class($element);
        $this->resolveElementClasses($elementClass);
        /** @var Decorator $decoratorClass */
        if (count(array_intersect($this->elementClasses[$elementClass],$decoratorClass::getSupportedElements()))>0) {
            return true;
        }
        return false;
    }


    /**
     * Should this decorator be used?
     * (= Is it's group-ID stated under the config key 'htmlfactory.decorators').
     *
     * @param string $decoratorClass
     * @return bool
     */
    private function decoratorShouldBeRegistered(string $decoratorClass)
    {
        /** @var Decorator $decoratorClass */
        $decoratorGroupId = $decoratorClass::getGroupId();

        // If $decoratorGroupId is null, this decorator is always applicable.
        if (is_null($decoratorGroupId)) {
            return true;
        }

        // Otherwise we only return true, if the $decoratorGroupId
        // is stated under the config-key 'htmlfactory.decorators'.
        return array_search($decoratorGroupId,config('htmlfactory.decorators')) !== false;
    }
}