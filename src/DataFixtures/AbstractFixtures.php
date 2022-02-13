<?php

namespace App\DataFixtures;

use App\Config;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AbstractFixtures extends Fixture
{

    private $uniqueElements = [];

    protected $hasher;
    protected $slugger;
    protected $faker;
    protected $entityManager;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager
    ) {
        $this->hasher = $hasher;
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;

        $this->faker = \Faker\Factory::create();
        $this->faker->addProvider(new PicsumPhotosProvider($this->faker));
    }

    public function load(ObjectManager $manager): void
    {
        // set in App\DataFixtures\AppFixtures
    }

    /**
     * Set a value for a given field based on a probability or leave the field empty
     *
     * @param  string $fieldName
     * @param  mixed  $value       Value to set if
     * @param  int    $probability Probability to set a value. Percent between 0 and 100.
     * @return void
     */
    public function setOptional(object $entity, string $fieldName, $value, int $probability = 100)
    {
        $setterFn = 'set' . ucFirst($fieldName);
        if (random_int(1, 100) < $probability) {
            $entity->$setterFn($value);
        }
    }

    /**
     * Set a healine for a content Entity (Post, Lesson, Project) 
     * based on a probability or leave the field empty
     *
     * @param  object $entity
     * @param  int    $probability Percent between 0 and 100
     * @param  string|null $setterFn Alternative setter function name if the property 
     * is different from 'setHeadline' (eg. 'setDescription)
     * @return void
     */
    public function setHeadline(object $entity, int $probability = 100, ?string $setterFn = null)
    {
        if (random_int(1, 100) < $probability) {
            $headline = $this->faker->paragraph(5, true);
            if (strlen($headline) > Config::HEADLINE_MAX_LENGTH) { // cut headline if to long
                $headline = substr($headline, 0, Config::HEADLINE_MAX_LENGTH);
            }
            if (!$setterFn) {
                $entity->setHeadline($headline);
            } else {
                $entity->$setterFn($headline);
            }
        }
    }

    /**
     * Set a description for a content Entity (Post, Lesson, Project) 
     * based on a probability or leave the field empty
     *
     * @param  object $entity
     * @param  int    $probability Percent between 0 and 100
     * @return void
     */
    public function setDescription(object $entity, int $probability = 100)
    {
        $this->setHeadline($entity, $probability, 'setDescription');
    }

    /**
     * Get a unique Value (eg. a label, a User object, etc.)
     * Check that the value has not already been used for another entity. If yes, generates a new value
     * @param string $entitySlug eg. 'post', 'lesson', project'
     * @param callable $callable The function used to generate the unique value
     * @return mixed The unique value 
     */
    public function uniqueValue(string $entitySlug, callable $generator)
    {
        $value = $generator();
        if (!isset($this->uniqueElements[$entitySlug])) {
            $this->uniqueElements[$entitySlug] = [];
        }
        while (in_array($value, $this->uniqueElements[$entitySlug])) {
            $value = $generator();
        }
        $this->uniqueElements[$entitySlug][] = $value;
        return $value;
    }
}
