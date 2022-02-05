<?php

namespace App\Tests\PHPUnit;

use App\Entity\PostCategory;
use App\Tests\PHPUnitEntityAbstract;

class PostCategoryUnitTest extends PHPUnitEntityAbstract
{
    
    private $entityClass = PostCategory::class;

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