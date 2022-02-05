<?php

namespace App\Tests\PHPUnit;

use App\Entity\ProjectCategory;
use App\Tests\PHPUnitEntityAbstract;

class ProjectCategoryUnitTest extends PHPUnitEntityAbstract
{
    
    private $entityClass = ProjectCategory::class;

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