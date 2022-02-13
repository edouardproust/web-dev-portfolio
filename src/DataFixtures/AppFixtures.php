<?php

namespace App\DataFixtures;

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
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends AbstractFixtures
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
        'repository' => 'www.github.com'
    ];
    const PROJECT_CATEGORIES_NB = 5;
    const USERS_NB = 3;
    const IMAGE_DEFAULT = [
        'width' => 800,
        'height' => 400,
    ];

    protected $adminOptions = [];
    protected $authors = [];
    protected $codingLanguages = [];
    protected $lessons = [];
    protected $lessonCategories = [];
    protected $posts = [];
    protected $postCategories = [];
    protected $projects = [];
    protected $projectCategories = [];
    protected $users = [];

    public function load(ObjectManager $manager): void
    {
        $this->runAndPersistAll([
            'createAdminOptions',
            'createUsers',
            'createAuthors',
            'createCodingLanguages',
            'createProjectCategories',
            'createPostCategories',
            'createLessonCategories',
            'createProjects',
            'createPosts',
            'createLessons'
        ]);
        $manager->flush();
    }

    protected function createAdminOptions()
    {
        foreach (self::ADMIN_OPTIONS as $slug => $value) {
            $option = (new AdminOption)
                ->setSlug($slug)
                ->setValue($value);
            $this->adminOptions[] = $option;
        }
    }

    protected function createAuthors()
    {
        for ($a = 0; $a < self::AUTHORS_NB; $a++) {
            $author = (new Author)
                ->setAvatar($this->faker->imageUrl(60, 60, true))
                ->setBio($this->faker->paragraph(5, true))
                ->setFullName(
                    $this->faker->firstName() . ' ' . $this->faker->lastName()
                );
            $users = $this->users;
            $user = $this->uniqueValue('authors', function () use ($users) {
                return $this->faker->randomElement($users);
            });
            $author->setUser($user);

            // optional fields
            foreach ([
                [$author, 'contactEmail', self::AUTHOR_DEFAUT['contactEmail'], 70],
                [$author, 'github', self::AUTHOR_DEFAUT['github'], 90],
                [$author, 'linkedin', self::AUTHOR_DEFAUT['linkedin'], 50],
                [$author, 'stackoverflow', self::AUTHOR_DEFAUT['stackoverflow'], 70],
                [$author, 'website', self::AUTHOR_DEFAUT['website'], 30]
            ] as $data) {
                $this->setOptional($data[0], $data[1], $data[2], $data[3]);
            }
            $this->authors[] = $author;
        }
    }

    protected function createCodingLanguages()
    {
        foreach (self::CONDING_LANGUAGES as $key => $value) {
            $codingLanguage = (new CodingLanguage)
                ->setLabel($value)
                ->setSlug($key);
            $this->codingLanguages[] = $codingLanguage;
        }
    }

    protected function createLessons()
    {
        for ($l = 0; $l < self::LESSONS_NB; $l++) {
            $title = $this->faker->sentence();
            $lesson = (new Lesson)
                ->setAuthor($this->faker->randomElement($this->authors))
                ->setCodingLanguage(
                    $this->faker->randomElement($this->codingLanguages)
                )
                ->setContent($this->faker->paragraphs($this->faker->numberBetween(5, 10), true))
                ->setCreatedAt($this->faker->dateTimeBetween('-1 year', '-1 hour'))
                ->setSlug(strtolower($this->slugger->slug($title)))
                ->setTitle($title);

            $this->setOptional($lesson, 'url', self::LESSON_DEFAULT['url'], 70);
            $this->setOptional($lesson, 'videoUrl', self::LESSON_DEFAULT['videoUrl'], 70);
            $this->setOptional($lesson, 'repository', self::LESSON_DEFAULT['repository'], 70);

            $this->setHeadline($lesson, 70);
            $lesson->addCategory($this->faker->randomElement($this->lessonCategories));
            $this->createAndAddComments($lesson, 'setLesson');

            $this->lessons[] = $lesson;
        }
    }

    protected function createLessonCategories()
    {
        foreach (self::LESSON_CATEGORIES as $key => $value) {
            $lessonCategory = new LessonCategory;
            $label = $this->uniqueValue('lesson', function () {
                return ucFirst($this->faker->words(1, true));
            });
            $lessonCategory
                ->setLabel($label)
                ->setSlug(strtolower($label));
            $this->setDescription($lessonCategory); // description

            $this->lessonCategories[] = $lessonCategory;
        }
    }

    protected function createPosts()
    {
        for ($p = 0; $p < self::POSTS_NB; $p++) {
            $title = $this->faker->sentence();
            $post = (new Post)
                ->setAuthor($this->faker->randomElement($this->authors))
                ->setContent($this->faker->paragraphs($this->faker->numberBetween(5, 10), true))
                ->setCreatedAt($this->faker->dateTimeBetween('-1 year', '-1 hour'))
                ->setSlug(strtolower($this->slugger->slug($title)))
                ->setTitle($title);
            // optional fields
            $mainImage = $this->faker->imageUrl(
                self::IMAGE_DEFAULT['width'],
                self::IMAGE_DEFAULT['height'],
                true,
            );
            $this->setOptional($post, 'mainImage', $mainImage, 70);

            $this->setHeadline($post, 90);
            $post->addCategory($this->faker->randomElement($this->postCategories));
            $this->createAndAddComments($post, 'setPost');

            $this->posts[] = $post;
        }
    }

    protected function createPostCategories()
    {
        for ($pc = 0; $pc < self::POST_CATEGORIES_NB; $pc++) {
            $postCategory = new PostCategory;
            $label = $this->uniqueValue('post', function () {
                return ucFirst($this->faker->words(1, true));
            });
            $postCategory
                ->setLabel($label)
                ->setSlug(strtolower($label));
            $this->setDescription($postCategory); // description

            $this->postCategories[] = $postCategory;
        }
    }

    protected function createProjects()
    {
        for ($p = 0; $p < self::PROJECTS_NB; $p++) {
            $title = $this->faker->sentence();
            $project = (new Project)
                ->setAuthor($this->faker->randomElement($this->authors))
                ->setContent($this->faker->paragraphs($this->faker->numberBetween(5, 10), true))
                ->setCreatedAt($this->faker->dateTimeBetween('-1 year', '-1 hour'))
                ->setMainImage(
                    $this->faker->imageUrl(self::IMAGE_DEFAULT['width'], self::IMAGE_DEFAULT['height'], true)
                )
                ->setRepository(self::PROJECT_DEFAULT['repository'])
                ->setSlug(strtolower($this->slugger->slug($title)))
                ->setTitle($title)
                ->setUrl(self::PROJECT_DEFAULT['url']);
            // optional fields
            $this->setHeadline($project, 70);
            $this->setOptional($project, 'featured', true, 20);

            $project->addCategory($this->faker->randomElement($this->projectCategories));
            $project->addCodingLanguage($this->faker->randomElement($this->codingLanguages));
            $this->createAndAddComments($project, 'setProject');

            $this->projects[] = $project;
        }
    }

    protected function createProjectCategories()
    {
        for ($pc = 0; $pc < self::PROJECT_CATEGORIES_NB; $pc++) {
            $projectCategory = new ProjectCategory;
            $label = $this->uniqueValue('project', function () {
                return ucFirst($this->faker->words(1, true));
            });
            $projectCategory
                ->setLabel($label)
                ->setSlug(strtolower($label));
            $this->setDescription($projectCategory); // description

            $this->projectCategories[] = $projectCategory;
        }
    }

    protected function createUsers()
    {
        // admin
        $admin = (new User)
            ->setCreatedAt(new \Datetime('-1 year'))
            ->setEmail('contact@edouardproust.dev')
            ->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $this->users[] = $admin;

        // users
        for ($u = 0; $u < self::USERS_NB; $u++) {
            $user = (new User)
                ->setEmail(
                    strtolower(
                        $this->faker->firstName() . '.' . $this->faker->lastName()
                    ) . '@' . $this->faker->freeEmailDomain()
                )
                ->setCreatedAt($this->faker->dateTimeBetween('-6 months', 'yesterday'));
            $user->setPassword($this->hasher->hashPassword($user, strtolower($this->faker->firstName())));
            $this->users[] = $user;
        }
    }

    protected function createAndAddComments(object $entity, string $setterFn)
    {
        $commentsNb = $this->faker->numberBetween(0, self::COMMENTS_MAX_NB_PER_POST);
        for ($c = 0; $c < $commentsNb; $c++) {
            $comment = (new Comment)
                ->$setterFn($entity)
                ->setCreatedAt($this->faker->dateTimeBetween($entity->getCreatedAt(), '-1 hour'))
                ->setContent($this->faker->paragraph(5, true))
                ->setFullName($this->faker->firstName());
            $this->entityManager->persist($comment);
            $entity->addComment($comment);
        }
    }
}
