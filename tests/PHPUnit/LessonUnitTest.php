<?php

namespace App\Tests\PHPUnit;

use App\Entity\Lesson;
use App\Tests\PHPUnitEntityAbstract;

class LessonUnitTest extends PHPUnitEntityAbstract
{
    
    private $entityClass = Lesson::class;

    public function testBasic(): void
    {
        $properties = [
            'createdAt' => $this->getNow(),
            'slug' => 'title',
            'title' => 'Title',
            'content' => 'This is a Lesson',
            'videoUrl' => 'www.youtube.com/lesson',
            'url' => 'lessons/mylesson/result',
            'repository' => 'www.github.com/myrepo',
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