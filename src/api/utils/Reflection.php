<?php declare(strict_types = 1);

/**
 * Reflection.php
 *
 * Файл является неотъемлемой частью проекта XEAF-RACK
 *
 * @author    Николай В. Анохин <n.anokhin@xeaf.net>
 * @copyright 2020 XEAF.NET Group
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
use XEAF\Rack\API\Traits\SingletonTrait;
use XEAF\Rack\API\Utils\Exceptions\CoreException;

/**
 * Реализует методы работы с отражениями
 *
 * @package XEAF\Rack\API\Utils
 */
class Reflection implements IReflection {

    use SingletonTrait;

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
     */
    public function createInjectable(string $className) {
        try {
            $result    = null;
            $refClass  = new ReflectionClass($className);
            $refMethod = $refClass->getConstructor();
            if ($refClass->implementsInterface(INamedObject::class)) {
                $result = Factory::getFactoryNamedObject($className, Factory::DEFAULT_NAME);
            } elseif ($refClass->implementsInterface(IFactoryObject::class)) {
                $result = Factory::getFactoryObject($className);
            } else {
                $args   = $this->injectMethodArgs($refMethod);
                $result = $refClass->newInstanceArgs($args);
            }
            return $result;
        } catch (ReflectionException $re) {
            throw CoreException::internalReflectionError($re);
        }
    }

    /**
     * @inheritDoc
     */
    public function returnInjectable(object $object, string $method) {
        try {
            $className = get_class($object);
            $refMethod = new ReflectionMethod($className, $method);
            $args      = $this->injectMethodArgs($refMethod);
            // if ($refMethod->isPrivate() || $refMethod->isProtected()) {
            if ($refMethod->isProtected()) {
                $refMethod->setAccessible(true);
            }
            return $refMethod->invokeArgs($object, $args);
        } catch (ReflectionException $re) {
            throw CoreException::internalReflectionError($re);
        }
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
                $result[] = Factory::getFactoryNamedObject($paramClassName, Factory::DEFAULT_NAME);
            } elseif ($paramClass->implementsInterface(IFactoryObject::class)) {
                $result[] = Factory::getFactoryObject($paramClassName);
            } else {
                $result[] = new $paramClassName();
            }
        }
        return $result;
    }
}
