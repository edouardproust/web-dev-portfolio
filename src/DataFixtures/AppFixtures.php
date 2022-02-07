<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Author;
use App\Entity\Lesson;
use App\Entity\Comment;
use App\Entity\Project;
use App\Entity\AdminOption;
use App\Entity\PostCategory;
use App\Entity\CodingLanguage;
use App\Entity\LessonCategory;
use App\Entity\ProjectCategory;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    const ADMIN_OPTIONS = [
        'siteName' => 'Edouard Proust Portfolio',
    ];
    const AUTHORS_NB = 2;
    const AUTHOR_DEFAUT = [
        'contactEmail' => 'contact@edouardproust.dev',
        'github' => 'www.github.com',
        'linkedin' => 'www.linkedin.com',
        'stackoverflow' => 'www.stackoverflow.com',
        'website' => 'www.edouardproust.dev'
    ];
    const CONDING_LANGUAGES = [
        'php' => 'PHP',
        'js' => 'Javascript',
        'css' => 'CSS'
    ];
    const COMMENTS_MAX_NB_PER_POST = 5;
    const LESSONS_NB = 20;
    const LESSON_DEFAULT = [
        'url' => 'lesson_url',
        'videoUrl' => 'www.youtube.com',
        'repository' => 'www.github.com'
    ];
    const LESSON_CATEGORIES = [
        'course' => 'Course',
        'exercise' => 'Exercice',
        'project' => 'Project'
    ];
    const POSTS_NB = 20;
    const POST_CATEGORIES_NB = 5;
    const PROJECTS_NB =  20;
    const PROJECT_DEFAULT = [
        'url' => 'project_url',
        'repository' => 'www.github.com',
        'descriptionLength' => 255
    ];
    const PROJECT_CATEGORIES_NB = 5;
    const USERS_NB = 10;
    const IMAGE_DEFAULT = [
        'width' => 800,
        'height' => 400,
    ];

    private $adminOptions = [];
    private $authors = [];
    private $codingLanguages = [];
    private $lessons = [];
    private $lessonCategories = [];
    private $posts = [];
    private $postCategories = [];
    private $projects = [];
    private $projectCategories = [];
    private $users = [];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SluggerInterface
     */
    private $slugger;
    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;
    private $faker;

    public function __construct(
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        UserPasswordHasherInterface $hasher
    ) {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
        $this->hasher = $hasher;

        $this->faker = \Faker\Factory::create();
        $this->faker->addProvider(new PicsumPhotosProvider($this->faker));
    }

    public function load(ObjectManager $manager): void
    {
        $this->createAdminOptions();
        foreach ($this->adminOptions as $adminOption) {
            $manager->persist($adminOption);
        }
        $this->createUsers();
        foreach ($this->users as $user) {
            $manager->persist($user);
        }
        $this->createAuthors();
        foreach ($this->authors as $author) {
            $manager->persist($author);
        }
        $this->createCodingLanguages();
        foreach ($this->codingLanguages as $codingLanguage) {
            $manager->persist($codingLanguage);
        }
        $this->createProjectCategories();
        foreach ($this->projectCategories as $projectCategory) {
            $manager->persist($projectCategory);
        }
        $this->createPostCategories();
        foreach ($this->postCategories as $postCategory) {
            $manager->persist($postCategory);
        }
        $this->createLessonCategories();
        foreach ($this->lessonCategories as $lessonCategory) {
            $manager->persist($lessonCategory);
        }
        $this->createProjects();
        foreach ($this->projects as $project) {
            $manager->persist($project);
        }
        $this->createPosts();
        foreach ($this->posts as $post) {
            $manager->persist($post);
        }
        $this->createLessons();
        foreach ($this->lessons as $lesson) {
            $manager->persist($lesson);
        }
        $manager->flush();
    }

    private function createAdminOptions()
    {
        foreach (self::ADMIN_OPTIONS as $slug => $value) {
            $option = (new AdminOption)
                ->setSlug($slug)
                ->setValue($value)
            ;
            $this->adminOptions[] = $option;
        }
    }

    private function createAuthors()
    {
        $usersSetAsAuthor = [];
        for ($a = 0; $a < self::AUTHORS_NB; $a++) {
            $author = (new Author)
                ->setAvatar($this->faker->imageUrl(60, 60))
                ->setBio($this->faker->paragraph(5, true))
            ;
            $randomUser = $this->faker->randomElement($this->users);
            //> security
            while (in_array($randomUser, $usersSetAsAuthor)) {
                $randomUser = $this->faker->randomElement($this->users);
            }
            $usersSetAsAuthor[] = $randomUser;
            //< security
            $author->setUser($randomUser);
            
            // optional fields
            if (random_int(1, 100) < 70) {
                $author->setContactEmail(self::AUTHOR_DEFAUT['contactEmail']);
            }
            if (random_int(1, 100) < 90) {
                $author->setGithub(self::AUTHOR_DEFAUT['github']);
            }
            if (random_int(1, 100) < 50) {
                $author->setLinkedin(self::AUTHOR_DEFAUT['linkedin']);
            }
            if (random_int(1, 100) < 70) {
                $author->setStackoverflow(self::AUTHOR_DEFAUT['stackoverflow']);
            }
            if (random_int(1, 100) < 30) {
                $author->setWebsite(self::AUTHOR_DEFAUT['website']);
            }

            $this->authors[] = $author;
        }
    }

    private function createCodingLanguages()
    {
        foreach (self::CONDING_LANGUAGES as $key => $value) {
            $codingLanguage = (new CodingLanguage)
                ->setLabel($value)
                ->setSlug($key)
            ;
            $this->codingLanguages[] = $codingLanguage;
        }
    }

    private function createLessons()
    {
        for ($l = 0; $l < self::LESSONS_NB; $l++) {
            $title = $this->faker->sentence();
            $lesson = (new Lesson)
                ->setAuthor($this->faker->randomElement($this->authors))
                ->setCodingLanguage($this->faker->randomElement($this->codingLanguages))
                ->setContent($this->faker->paragraphs($this->faker->numberBetween(5, 10), true))
                ->setCreatedAt($this->faker->dateTimeBetween('-1 year', '-1 hour'))
                ->setSlug(strtolower($this->slugger->slug($title)))
                ->setTitle($title)
            ;
            // optional fields
            if (random_int(1, 100) < 70) {
                $lesson->setUrl(self::LESSON_DEFAULT['url']);
            }
            if (random_int(1, 100) < 70) {
                $lesson->setVideoUrl(self::LESSON_DEFAULT['videoUrl']);
            }
            if (random_int(1, 100) < 70) {
                $lesson->setRepository(self::LESSON_DEFAULT['repository']);
            }

            $lesson->addCategory($this->faker->randomElement($this->lessonCategories));
            $this->createAndAddComments($lesson, 'setLesson');

            $this->lessons[] = $lesson;
        }
    }

    private function createLessonCategories()
    {
        foreach (self::LESSON_CATEGORIES as $key => $value) {
            $lessonCategory = (new LessonCategory)
                ->setLabel($value)
                ->setSlug($key)
            ;
            $this->lessonCategories[] = $lessonCategory;
        }
    }

    private function createPosts()
    {
        for ($p = 0; $p < self::POSTS_NB; $p++) {
            $title = $this->faker->sentence();
            $post = (new Post)
                ->setAuthor($this->faker->randomElement($this->authors))
                ->setContent($this->faker->paragraphs($this->faker->numberBetween(5, 10), true))
                ->setCreatedAt($this->faker->dateTimeBetween('-1 year', '-1 hour'))
                ->setSlug(strtolower($this->slugger->slug($title)))
                ->setTitle($title)
            ;
            // optional fields
            if (random_int(1, 100) < 70) {
                $post->setMainImage(
                    $this->faker->imageUrl(
                        self::IMAGE_DEFAULT['width'],
                        self::IMAGE_DEFAULT['height']
                    )
                );
            }

            $post->addCategory($this->faker->randomElement($this->postCategories));
            $post->addCodingLanguage($this->faker->randomElement($this->codingLanguages));
            $this->createAndAddComments($post, 'setPost');

            $this->posts[] = $post;
        }
    }

    private function createPostCategories()
    {
        $setLabels = [];
        for ($pc = 0; $pc < self::POST_CATEGORIES_NB; $pc++) {
            $label = ucFirst($this->faker->words(1, true));
            //> security
            while (in_array($label, $setLabels)) {
                $label = ucFirst($this->faker->words(1, true));
            }
            $setLabels[] = $label;
            //< security
            $postCategory = (new PostCategory)->setLabel($label);
            $postCategory->setSlug(strtolower($label));
            $this->postCategories[] = $postCategory;
        }
    }

    private function createProjects()
    {
        for ($p = 0; $p < self::PROJECTS_NB; $p++) {
            $title = $this->faker->sentence();
            $project = (new Project)
                ->setAuthor($this->faker->randomElement($this->authors))
                ->setContent($this->faker->paragraphs($this->faker->numberBetween(5, 10), true))
                ->setCreatedAt($this->faker->dateTimeBetween('-1 year', '-1 hour'))
                ->setMainImage(
                    $this->faker->imageUrl(self::IMAGE_DEFAULT['width'], self::IMAGE_DEFAULT['height'])
                )
                ->setRepository(self::PROJECT_DEFAULT['repository'])
                ->setSlug(strtolower($this->slugger->slug($title)))
                ->setTitle($title)
                ->setUrl(self::PROJECT_DEFAULT['url'])
            ;
            // optional fields
            if (random_int(1, 100) < 70) {
                $description = $this->faker->paragraph(5, true);
                if (strlen($description) > self::PROJECT_DEFAULT['descriptionLength']) {
                    $description = substr($description, 0, self::PROJECT_DEFAULT['descriptionLength']);
                }
                $project->setDescription($description);
            }

            $project->addCategory($this->faker->randomElement($this->projectCategories));
            $project->addCodingLanguage($this->faker->randomElement($this->codingLanguages));
            $this->createAndAddComments($project, 'setProject');

            $this->projects[] = $project;
        }
    }

    private function createProjectCategories()
    {
        $setLabels = [];
        for ($pc = 0; $pc < self::PROJECT_CATEGORIES_NB; $pc++) {
            $label = ucFirst($this->faker->words(1, true));
            //> security
            while (in_array($label, $setLabels)) {
                $label = ucFirst($this->faker->words(1, true));
            }
            $setLabels[] = $label;
            //< security
            $projectCategory = (new ProjectCategory)
                ->setLabel($label)
                ->setSlug(strtolower($label))
            ;
            $this->projectCategories[] = $projectCategory;
        }
    }

    private function createUsers()
    {
        // admin
        $admin = (new User)
            ->setCreatedAt(new Datetime('-1 year'))
            ->setEmail('contact@edouardproust.dev')
            ->setRoles(['ROLE_ADMIN'])
        ;
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $this->users[] = $admin;

        // users
        for ($u = 0; $u < self::USERS_NB; $u++) {
            $user = (new User)
                ->setEmail(strtolower(
                    $this->faker->firstName().'.'.$this->faker->lastName()
                ).'@'.$this->faker->freeEmailDomain())
                ->setCreatedAt($this->faker->dateTimeBetween('-6 months', 'yesterday'));
            ;
            $user->setPassword($this->hasher->hashPassword($user, strtolower($this->faker->firstName())));
            $this->users[] = $user;
        }
    }

    private function createAndAddComments(object $entity, string $setterFn)
    {
        $commentsNb = $this->faker->numberBetween(0, self::COMMENTS_MAX_NB_PER_POST);
        for ($c = 0; $c < $commentsNb; $c++) {
            $comment = (new Comment)
                ->$setterFn($entity)
                ->setCreatedAt($this->faker->dateTimeBetween($entity->getCreatedAt(), '-1 hour'))
                ->setContent($this->faker->paragraph(5, true))
                ->setUser($this->faker->randomElement($this->users))
            ;
            $this->entityManager->persist($comment);
            $entity->addComment($comment);
        }
    }
}
