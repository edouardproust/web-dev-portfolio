<?php

namespace App\Tests\PHPUnit;

use App\Entity\Comment;
use App\Tests\PHPUnitEntityAbstract;

class CommentUnitTest extends PHPUnitEntityAbstract
{
    private $entityClass = Comment::class;

    public function testBasic(): void
    {
        $properties = [
            'createdAt' => $this->getNow(),
            'content' => 'This is my comment',
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
