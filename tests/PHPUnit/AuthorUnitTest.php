<?php

namespace App\Tests\PHPUnit;

use App\Entity\User;
use App\Entity\Author;
use App\Tests\PHPUnitEntityAbstract;

class AuthorUnitTest extends PHPUnitEntityAbstract
{
    private static $entityClass = Author::class;

    public function testBasic(): void
    {
        $properties = [
            'avatar' => 'img/avatar.png',
            'bio' => 'This is my bio',
            'contactEmail' => 'contact@me.com',
            'github' =>  'www.github.com/myprofile',
            'linkedin' => 'www.linkedin.com/myprofile',
            'stackoverflow' => 'www.stackoverflow.com/myprofile',
            'website' => 'www.mywebsite.com'
        ];
        $this->processIsTrue(new self::$entityClass, $properties);
        $this->processIsFalse(new self::$entityClass, $properties);
        $this->processIsEmpty(new self::$entityClass, $properties);
    }

    public function testRelations(): void
    {
        $properties = [
            'user' => new User,
        ];
        $this->processIsTrue(new self::$entityClass, $properties);
        $this->processIsFalse(new self::$entityClass, $properties);
        $this->processIsEmpty(new self::$entityClass, $properties);
    }
}
