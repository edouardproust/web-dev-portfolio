<?php

namespace App\DataFixtures;

use App\Config;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractFixtures extends Fixture
{
    private $uniqueElements = [];

    protected $hasher;
    protected $slugger;
    protected $faker;
    protected $entityManager;
    protected $urlGenerator;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->hasher = $hasher;
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;

        $this->faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        // set in App\DataFixtures\AppFixtures
    }

    /**
     * Run and persists methods in the provided order
     * @param array $methods Array of method names.
     * Method names must start by 'create' (eg. 'createUsers', 'createPosts')
     * @return void
     */
    protected function runAndPersistAll(array $methods): void
    {
        foreach ($methods as $method) {
            $this->runAndPersist($method);
        }
    }

    /**
     * Run a given function and persist the generated entities
     * - Before running this function you must set a property (array type)
     * to store the generated entities
     * @param string $method The method name. Must start by 'create' (eg. 'createUsers', 'createPosts')
     * @return void
     */
    protected function runAndPersist(string $method): void
    {
        $entities = lcfirst(str_replace('create', '', $method));
        $this->$method();
        foreach ($this->$entities as $entity) {
            $this->entityManager->persist($entity);
        }
    }

    /**
     * Set a value for a given field based on a probability or leave the field empty
     *
     * @param  string $fieldName
     * @param  mixed  $value       Value to set if
     * @param  int    $probability Probability to set a value. Percent between 0 and 100.
     * @return void
     */
    protected function setOptional(object $entity, string $fieldName, $value, int $probability = 100)
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
    protected function setHeadline(object $entity, int $probability = 100, ?string $setterFn = null)
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
    protected function setDescription(object $entity, int $probability = 100)
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
    protected function uniqueValue(string $entitySlug, callable $generator)
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
