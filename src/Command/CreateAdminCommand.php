<?php

namespace App\Command;

use App\Entity\User;
use App\Helpers\DateTimeHelperTrait;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create an admin user',
)]
class CreateAdminCommand extends Command
{

    use DateTimeHelperTrait;

    public function __construct(
        private EntityManagerInterface $manager,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::OPTIONAL, 'Admin username')
            ->addArgument('email', InputArgument::OPTIONAL, 'Admin email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Admin password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        if (!$username) {
            $question = new Question('Nom d\'utilisateur : ');
            $username = $helper->ask($input, $output, $question);
        }

        $email = $input->getArgument('email');
        if (!$email) {
            $question = new Question('Adresse e-mail : ');
            $email = $helper->ask($input, $output, $question);
        }

        $password = $input->getArgument('password');
        if (!$password) {
            $question = new Question('Mot de passe : ');
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $password = $helper->ask($input, $output, $question);
        }

        $user = (new User);
        $user
            ->setEmail($email)
            ->setRoles(['ROLE_ADMIN'])
            ->setUsername($username)
            ->setPassword($password)
            ->setRegisteredAt($this->now())
            ->setPassword($this->hasher->hashPassword($user, $password));

        try {
            $this->manager->persist($user);
            $this->manager->flush();

            $io->success('A new admin user has been created ! 🚀');

            return Command::SUCCESS;
        } catch (ORMException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        } catch (Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}