<?php

namespace App\Tests\PHPUnit;

use App\Entity\CodingLanguage;
use App\Tests\PHPUnitEntityAbstract;

class CodingLanguageUnitTest extends PHPUnitEntityAbstract
{
    private $entityClass = CodingLanguage::class;

    public function testBasic(): void
    {
        $properties = [
            'slug' => 'php',
            'label' => 'PHP',
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
