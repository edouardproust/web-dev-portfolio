<?php

namespace App\Tests\PHPUnit;

use App\Entity\AdminOption;
use App\Tests\PHPUnitEntityAbstract;

class AdminOptionUnitTest extends PHPUnitEntityAbstract
{
    private static $entityClass = AdminOption::class;

    public function testBasic(): void
    {
        $properties = [
            'constant' => 'option',
            'value' => 'Option'
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
