<?php

namespace App\Tests\PHPUnit;

use App\Entity\User;
use App\Tests\PHPUnitEntityAbstract;

class UserUnitTest extends PHPUnitEntityAbstract
{
    
    private $entityClass = User::class;

    public function testBasic(): void
    {
        $properties = [
            'createdAt' => $this->getNow(),
            'email' => 'test@email.com',
            'password' => '********',
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