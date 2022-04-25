<?php

namespace App\Command;

use App\DataFixtures\AppFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * - Command: `php bin/console app:create:admin <username> <password> <?fullname>`
 * - Alias: `php bin/console a:c:a <username> <password> <?fullname>`
 * @package App\Command
 */
class CreateAdminCommand extends Command
{

    const REQUIRE_USERNAME = true;
    const REQUIRE_PASSWORD = true;

    private $entityManager;
    private $hasher;
    private $slugger;
    private $urlGenerator;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $hasher,
        SluggerInterface $slugger,
        UrlGeneratorInterface $urlGenerator
    ) {
        // call here properties used in configure()
        // ...

        parent::__construct();

        // call here properties used in execute()
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
        $this->slugger = $slugger;
        $this->urlGenerator = $urlGenerator;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:create:admin')
            ->setAliases([
                'a:c:a'
            ])
            ->setDescription('Creates a new admin.')
            ->setHelp(
                "This command allows you to create a new admininistor for this website."
            )
            ->addArgument(
                'username',
                self::REQUIRE_USERNAME ? InputArgument::REQUIRED : InputArgument::OPTIONAL,
                'The username of the admin.'
            )
            ->addArgument(
                'password',
                self::REQUIRE_PASSWORD ? InputArgument::REQUIRED : InputArgument::OPTIONAL,
                'The password of the admin.'
            )
            ->addArgument(
                'fullname',
                InputArgument::OPTIONAL,
                'Give this admin a fullname (firstname + lastname). This field is optional.'
            )
            ->setHidden(false);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fixtures = new AppFixtures(
            $this->entityManager,
            $this->hasher,
            $this->slugger,
            $this->urlGenerator
        );
        $fixtures->runAndPersist('createAdmin', [
            $input->getArgument('username'),
            $input->getArgument('password')
        ]);
        $fixtures->runAndPersist('createAuthors', [
            1, // create only 1 user (the admin),
            true, // set only mandatory properties
            $input->getArgument('fullname') ?? null
        ]);
        $this->entityManager->flush();

        $output
            ->writeln([ //outputs a message followed by a "\n".
                '-----------------',
                'New admin created:',
                '- Username: ' . $input->getArgument('username'),
                '- Password: ' . $input->getArgument('password'),
                '-----------------',
            ]);
        return Command::SUCCESS; // equivalent to returning int(0) = no problem running the command
        // return Command::FAILURE; // equivalent to returning int(1) = some error happened during the execution
        // return Command::INVALID // equivalent to returning int(2) = incorrect command usage (e.g. invalid options)
    }
}
