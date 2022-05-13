<?php

namespace App\Tests\PHPUnit;

use App\Entity\LessonCategory;
use App\Tests\PHPUnitEntityAbstract;

class LessonCategoryUnitTest extends PHPUnitEntityAbstract
{
    private static $entityClass = LessonCategory::class;

    public function testBasic(): void
    {
        $properties = [
            'slug' => 'myCategory',
            'label' => 'My Category',
            'description' => 'This is a description',
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
