<?php

namespace App\Tests;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

abstract class PHPUnitEntityAbstract extends TestCase
{

    /** @var DateTimeImmutable */
    protected $now;

    private function setNow(): void
    {
        if(!$this->now) $this->now = new DateTimeImmutable();
    }

    protected function getNow(): DateTimeImmutable
    {
        if(!$this->now) $this->setNow();
        return $this->now;
    }

    protected function processTrueFalseAndEmptyTests(string $entityClass, array $properties)     
    {
        $this->processIsTrue(new $entityClass, $properties);
        $this->processIsFalse(new $entityClass, $properties);
        $this->processIsEmpty(new $entityClass, $properties);
    }

    protected function processIsTrue(object $entity, array $properties)
    {
        foreach ($properties as $key => $value) {
            [$setterFn, $getterFn] = $this->getGetterAndSetter($key);
            $entity->$setterFn($value);

            if(method_exists($entity, 'getPassword')) {
                dump($entity);
            }

            $this->assertTrue($entity->$getterFn() === $value);
        } 
    }

    public function processIsFalse(object $entity, array $properties)
    {
        foreach ($properties as $key => $value) {
            [$setterFn, $getterFn] = $this->getGetterAndSetter($key);
            $entity->$setterFn($value);
            $this->assertFalse($entity->$getterFn() === 'false');
        } 
    }

    public function processIsEmpty(object $entity, array $properties)
    {
        foreach ($properties as $key => $value) {
            [$setterFn, $getterFn] = $this->getGetterAndSetter($key);
            $this->assertEmpty($entity->$getterFn());
        }
    }

    private function getGetterAndSetter(string $property): array
    {
        $formatedProperty = ucFirst($property);
        return [
            $setterFn = 'set' . $formatedProperty,
            $getterFn = 'get' . $formatedProperty,
        ];
    }

}