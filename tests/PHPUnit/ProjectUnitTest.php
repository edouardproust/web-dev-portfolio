<?php

namespace App\Tests\PHPUnit;

use App\Entity\Project;
use App\Tests\PHPUnitEntityAbstract;

class ProjectUnitTest extends PHPUnitEntityAbstract
{
    private $entityClass = Project::class;

    public function testBasic(): void
    {
        $properties = [
            'createdAt' => $this->getNow(),
            'slug' => 'project',
            'title' => 'Project',
            'headline' => 'This a project.',
            'content' => 'This project is awesome!',
            'mainImage' => 'img/posts/mypostimg',
            'url' => 'projects/project/show',
            'repository' => 'github.com/myrepo',
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
