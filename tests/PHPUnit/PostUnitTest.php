<?php

namespace App\Tests\PHPUnit;

use App\Entity\Post;
use App\Tests\PHPUnitEntityAbstract;

class PostUnitTest extends PHPUnitEntityAbstract
{
    private $entityClass = Post::class;

    public function testBasic(): void
    {
        $properties = [
            'createdAt' => $this->getNow(),
            'slug' => 'title',
            'title' => 'Title',
            'headline' => 'This a post.',
            'content' => 'This is a post content.',
            'mainImage' => 'img/posts/mypostimg',
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
