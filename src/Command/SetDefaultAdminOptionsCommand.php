<?php

namespace App\Command;

use App\DataFixtures\AppFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * - Command: `php bin/console app:create:options`
 * - Alias: `php bin/console a:c:o`
 * - Equivalent of `php bin/console doctrine:fixtures:load --group=prod -n` (alias: `php bin/console d:f:l --group=prod -n`)
 * @package App\Command
 */
class SetDefaultAdminOptionsCommand extends Command
{
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
            ->setName('app:create:options')
            ->setAliases([
                'a:c:o'
            ])
            ->setDescription('Generate Default Admin Options.')
            ->setHelp(
                "This command allows you to generate default admin options for this website. Eg: Website name and domain, contact informations, notifications preferences, etc."
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
        $fixtures->runAndPersist('createAdminOptions');
        $this->entityManager->flush();

        $output
            ->writeln([ //outputs a message followed by a "\n".
                '-----------------',
                'Default admin options generated!',
                '-----------------',
            ]);
        return Command::SUCCESS; // equivalent to returning int(0) = no problem running the command
        // return Command::FAILURE; // equivalent to returning int(1) = some error happened during the execution
        // return Command::INVALID // equivalent to returning int(2) = incorrect command usage (e.g. invalid options)
    }
}
