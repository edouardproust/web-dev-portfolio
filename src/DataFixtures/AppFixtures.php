<?php

namespace App\DataFixtures;

use App\Path;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Author;
use App\Entity\Lesson;
use App\Entity\Comment;
use App\Entity\Project;
use App\Entity\PostCategory;
use App\Entity\CodingLanguage;
use App\Entity\LessonCategory;
use App\Entity\ProjectCategory;
use App\DataFixtures\AdminOptions;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

/**
 * Fixtures group: 'dev'
 *
 * @package App\DataFixtures
 */
class AppFixtures extends AbstractFixtures implements FixtureGroupInterface
{
    const ADMIN_EMAIL = 'contact@edouardproust.dev';
    const ADMIN_PASSWORD = 'admin';

    const AUTHORS_NB = 2;
    const AUTHOR_DEFAUT = [
        'github' => 'https://www.github.com',
        'linkedin' => 'https://www.linkedin.com',
        'stackoverflow' => 'https://www.stackoverflow.com',
        'website' => 'https://www.edouardproust.dev'
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
        'videoUrl' => 'https://www.youtube.com',
        'repository' => 'https://www.github.com'
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
        'url' => 'https://www.edouardproust.dev',
        'repository' => 'https://www.github.com'
    ];
    const PROJECT_CATEGORIES_NB = 5;
    const USERS_NB = 3;

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

    public static function getGroups(): array
    {
        return ['dev'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->runAndPersistAll([
            'createAdmin',
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

    protected function createAdmin(?string $email = null, ?string $plainPassword = null)
    {
        if (!$email) {
            $email = self::ADMIN_EMAIL;
        }
        if (!$plainPassword) {
            $plainPassword = self::ADMIN_PASSWORD;
        }

        $admin = (new User)
            ->setCreatedAt(new \Datetime('now'))
            ->setEmail($email)
            ->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, $plainPassword));
        $this->users[] = $admin;
    }

    protected function createUsers()
    {
        for ($u = 0; $u < self::USERS_NB; $u++) {
            $firstname = $this->faker->firstName();
            $user = (new User)
                ->setEmail(
                    strtolower(
                        $firstname . '.' . $this->faker->lastName()
                    ) . '@' . $this->faker->freeEmailDomain()
                )
                ->setCreatedAt($this->faker->dateTimeBetween('-6 months', 'yesterday'));
            $user->setPassword($this->hasher->hashPassword($user, strtolower($firstname)));
            $this->users[] = $user;
        }
    }

    protected function createAuthors(
        ?int $authorsNb = null,
        bool $onlyMandatoryProps = false,
        ?string $fullname = null
    ) {
        if ($authorsNb === null) {
            $authorsNb = self::AUTHORS_NB;
        }

        // admin
        $admin = $this->users[0];
        $author = (new Author)
            ->setUser($admin)
            ->setAvatar(Path::AUTHOR_DEFAULT_IMG)
            ->setFullName($fullname ?? AdminOptions::get('CONTACT_NAME', 'value') ?? 'Anonymous')
            ->setBio("Author since " . $admin->getCreatedAt()->format('F d, Y'))
            ->setIsApproved(true);
        $author->setBio(
            $author->getFullName() . " is author on \"" . AdminOptions::get('SITE_NAME', 'value') .
                "\" since " . $admin->getCreatedAt()->format('F d, Y') . "."
        );
        if (!$onlyMandatoryProps) {
            $author
                ->setBio($this->faker->paragraph(5, true))
                ->setGithub(AdminOptions::get('SOCIAL_GITHUB', 'value'))
                ->setLinkedin(AdminOptions::get('SOCIAL_LINKEDIN', 'value'))
                ->setStackoverflow(AdminOptions::get('SOCIAL_STACKOVERFLOW', 'value'))
                ->setWebsite($this->urlGenerator->generate('home'));
        }
        $admin->setIsAuthor(true);
        $this->authors[] = $author;

        // other users
        for ($a = 0; $a < $authorsNb - 1; $a++) {
            $firstname = $this->faker->firstName();
            $author = (new Author)
                ->setAvatar(Path::AUTHOR_DEFAULT_IMG)
                ->setBio($this->faker->paragraph(5, true))
                ->setFullName($firstname . ' ' . $this->faker->lastName())
                ->setIsApproved(true);
            // user
            $users = $this->users;
            unset($users[0]); // remove admin as it is already used as an author
            $user = $this->uniqueValue('authors', function () use ($users) {
                return $this->faker->randomElement($users);
            });
            $author->setUser($user);
            $user
                ->setRoles(['ROLE_AUTHOR'])
                ->setIsAuthor(true);
            // optional fields
            $this->setOptional(
                $author,
                'contactEmail',
                strtolower($firstname) . '@' . AdminOptions::get('SITE_DOMAIN', 'value'),
                70
            );
            $this->setOptional($author, 'github', self::AUTHOR_DEFAUT['github'], 90);
            $this->setOptional($author, 'linkedin', self::AUTHOR_DEFAUT['linkedin'], 50);
            $this->setOptional($author, 'stackoverflow', self::AUTHOR_DEFAUT['stackoverflow'], 70);
            $this->setOptional($author, 'website', self::AUTHOR_DEFAUT['website'], 30);

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
            $this->setOptional($post, 'mainImage', Path::POST_DEFAULT_IMG, 70);

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
                ->setCompletedOn($this->faker->dateTimeBetween('-2 years', 'yesterday'))
                ->setCreatedAt($this->faker->dateTimeBetween('-1 year', '-1 hour'))
                ->setThumbnail(Path::PROJECT_DEFAULT_IMG)
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

    protected function createAndAddComments(object $entity, string $setterFn)
    {
        $commentsNb = $this->faker->numberBetween(0, self::COMMENTS_MAX_NB_PER_POST);
        for ($c = 0; $c < $commentsNb; $c++) {
            $comment = (new Comment)
                ->$setterFn($entity)
                ->setCreatedAt($this->faker->dateTimeBetween($entity->getCreatedAt(), '-1 hour'))
                ->setContent($this->faker->paragraph(5, true))
                ->setFullName($this->faker->firstName())
                ->setIsVisible(true);
            $this->entityManager->persist($comment);
            $entity->addComment($comment);
        }
    }
}
