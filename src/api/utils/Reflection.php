<?php

/**
 * Reflection.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2019 XEAF.NET Group
 *
 * @license   Apache 2.0
 */
namespace XEAF\Rack\API\Utils;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use XEAF\Rack\API\App\Factory;
use XEAF\Rack\API\App\Router;
use XEAF\Rack\API\Interfaces\IFactoryObject;
use XEAF\Rack\API\Interfaces\INamedObject;
use XEAF\Rack\API\Interfaces\IReflection;
use XEAF\Rack\API\Utils\Exceptions\CoreException;

/**
 * Реализует методы работы с отражениями
 *
 * @package XEAF\Rack\API\Utils
 */
class Reflection implements IReflection {

    /**
     * @inheritDoc
     */
    public function __construct() {
    }

    /**
     * @inheritDoc
     */
    public function classFileName(string $className): string {
        try {
            $ref = new ReflectionClass($className);
            return $ref->getFileName();
        } catch (ReflectionException $reason) {
            throw CoreException::internalReflectionError($reason);
        }
    }

    /**
     * @inheritDoc
     */
    public function moduleClassName(): string {
        $router = Router::getInstance();
        $params = Parameters::getInstance();
        return $router->routeClassName($params->getActionPath());
    }

    /**
     * @inheritDoc
     */
    public function moduleClassFileName(): string {
        $className = $this->moduleClassName();
        return $this->classFileName($className);
    }

    /**
     * @inheritDoc
     *
     * @throws \ReflectionException
     */
    public function createInjectable(string $className) {
        $result    = null;
        $refClass  = new ReflectionClass($className);
        $refMethod = $refClass->getConstructor();
        if ($refClass->implementsInterface(INamedObject::class)) {
            $result = Factory::getFactoryNamedObject($className, Factory::DEFAULT_NAME);
        } else if ($refClass->implementsInterface(IFactoryObject::class)) {
            $result = Factory::getFactoryObject($className);
        } else {
            $args   = $this->injectMethodArgs($refMethod);
            $result = $refClass->newInstanceArgs($args);
        }
        return $result;
    }

    /**
     * @inheritDoc
     *
     * @throws \ReflectionException
     */
    public function returnInjectable(object $object, string $method) {
        $className = get_class($object);
        $refMethod = new ReflectionMethod($className, $method);
        $args      = $this->injectMethodArgs($refMethod);
        if ($refMethod->isPrivate() || $refMethod->isProtected()) {
            $refMethod->setAccessible(true);
        }
        return $refMethod->invokeArgs($object, $args);
    }

    /**
     * Возвращает массв значений параметров
     *
     * @param \ReflectionMethod $method Объект отражения метода
     *
     * @return array
     */
    protected function injectMethodArgs(ReflectionMethod $method): array {
        $result    = [];
        $refParams = $method->getParameters();
        foreach ($refParams as $param) {
            $paramClass     = $param->getClass();
            $paramClassName = $paramClass->getName();
            if ($paramClass->implementsInterface(INamedObject::class)) {
                $result[] = Factory::getFactoryObject($paramClassName);
            } else if ($paramClass->implementsInterface(IFactoryObject::class)) {
                $result[] = Factory::getFactoryNamedObject($paramClassName, Factory::DEFAULT_NAME);
            } else {
                $result[] = new $paramClassName();
            }
        }
        return $result;
    }

    /**
     * Возвращает единичный экземпляр объекта класса
     *
     * @return \XEAF\Rack\API\Interfaces\IReflection
     */
    public static function getInstance(): IReflection {
        $result = Factory::getFactoryObject(self::class);
        assert($result instanceof IReflection);
        return $result;
    }
}
