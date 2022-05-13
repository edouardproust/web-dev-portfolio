<?php

namespace App\Tests\PHPUnit;

use App\Entity\Project;
use App\Tests\PHPUnitEntityAbstract;

class ProjectUnitTest extends PHPUnitEntityAbstract
{
    private static $entityClass = Project::class;

    public function testBasic(): void
    {
        $properties = [
            // 'createdAt' => $this->getNow(),
            'slug' => 'project',
            'title' => 'Project',
            'headline' => 'This a project.',
            'content' => 'This project is awesome!',
            'thumbnail' => 'img/posts/mypostimg',
            'url' => 'projects/project/show',
            'repository' => 'github.com/myrepo',
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
