<?php

namespace App\DataFixtures;

use App\Config;
use App\Entity\AdminOption;
use App\Helper\StringHelper;
use App\DataFixtures\AdminOptions;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractFixtures extends Fixture
{
    private $uniqueElements = [];

    protected $entityManager;
    protected $hasher;
    protected $slugger;
    protected $urlGenerator;
    protected $faker;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher,
        SluggerInterface $slugger,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
        $this->slugger = $slugger;
        $this->urlGenerator = $urlGenerator;

        $this->faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        // set in App\DataFixtures\AppFixtures
    }

    protected function createAdminOptions()
    {
        foreach (AdminOptions::getConstants() as $optionName => $optionData) {
            $option = (new AdminOption)
                ->setConstant($optionName)
                ->setType(AdminOptions::get($optionName, 'type'))
                ->setLabel(AdminOptions::get($optionName, 'label'))
                ->setHelp(AdminOptions::get($optionName, 'help'))
                ->setValue(AdminOptions::get($optionName, 'value'))
                ->setIsActive(AdminOptions::get($optionName, 'isActive'))
                ->setFile(AdminOptions::get($optionName, 'file'))
                ->setIsUploadable(AdminOptions::get($optionName, 'isUploadable'))
                ->setIsRequired(AdminOptions::get($optionName, 'isRequired'));
            $this->adminOptions[] = $option;
        }
    }

    /**
     * Run a given function and persist the generated entities
     * - Before running this function you must set a property (array type)
     * to store the generated entities
     * @param string $method The method name. Must start by 'create' (eg. 'createUsers', 'createPosts')
     * @param array $properties Properties for the given method, if it accepts some.
     * @return void
     */
    public function runAndPersist(string $method, array $properties = []): void
    {
        $entities = lcfirst(str_replace('create', '', $method));
        if ($entities === 'admin') {
            $entities = 'users';
        }
        $this->$method(...$properties);
        foreach ($this->$entities as $entity) {
            $this->entityManager->persist($entity);
        }
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
        if ($this->faker->boolean($probability)) {
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
                $headline = StringHelper::extract($headline, Config::HEADLINE_MAX_LENGTH - 3, '.');
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
