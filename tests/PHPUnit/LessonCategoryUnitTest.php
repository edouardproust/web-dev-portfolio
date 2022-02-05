<?php

namespace App\Tests\PHPUnit;

use App\Entity\LessonCategory;
use App\Tests\PHPUnitEntityAbstract;

class LessonCategoryUnitTest extends PHPUnitEntityAbstract
{
    
    private $entityClass = LessonCategory::class;

    public function testBasic(): void
    {
        $properties = [
            'slug' => 'myCategory',
            'label' => 'My Category',
            'description' => 'This is a description',
        ];
        $this->processTrueFalseAndEmptyTests($this->entityClass, $properties);
    }

    // public function testRelations(): void
    // {
    //     $properties = [
    //         'user' => new User,
    //     ];
    //     $this->processTrueFalseAndEmptyTests($this->entityClass, $properties);
    // }

}