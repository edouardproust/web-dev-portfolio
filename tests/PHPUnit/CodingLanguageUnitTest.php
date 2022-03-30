<?php

namespace App\Tests\PHPUnit;

use App\Entity\CodingLanguage;
use App\Tests\PHPUnitEntityAbstract;

class CodingLanguageUnitTest extends PHPUnitEntityAbstract
{
    private static $entityClass = CodingLanguage::class;

    public function testBasic(): void
    {
        $properties = [
            'slug' => 'php',
            'label' => 'PHP',
        ];
        $this->processIsTrue(new self::$entityClass, $properties);
        $this->processIsFalse(new self::$entityClass, $properties);
        $this->processIsEmpty(new self::$entityClass, $properties);
    }

    // public function testRelations(): void
    // {
    //     $properties = [
    //         'user' => new User,
    //     ];
    //     $this->processTrueFalseAndEmptyTests($this->entityClass, $properties);
    // }
}
