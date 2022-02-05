<?php

namespace App\Tests\PHPUnit;

use App\Entity\User;
use App\Entity\Author;
use App\Tests\PHPUnitEntityAbstract;

class AuthorUnitTest extends PHPUnitEntityAbstract
{

    private $entityClass = Author::class;

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
        $this->processTrueFalseAndEmptyTests($this->entityClass, $properties);
    }

    public function testRelations(): void
    {
        $properties = [
            'user' => new User,
        ];
        $this->processTrueFalseAndEmptyTests($this->entityClass, $properties);
    }

}