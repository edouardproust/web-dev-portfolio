<?php

namespace App\Tests\PHPUnit;

use App\Entity\Comment;
use App\Tests\PHPUnitEntityAbstract;

class CommentUnitTest extends PHPUnitEntityAbstract
{
    private static $entityClass = Comment::class;

    public function testBasic(): void
    {
        $properties = [
            'createdAt' => $this->getNow(),
            'content' => 'This is my comment',
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
