<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\BackofficeUser;
use App\Repository\BackofficeUserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultAdminCreateCommand extends Command
{
    protected static $defaultName = 'app:default-admin:create';

    protected static string $defaultDescription = 'Create a default admin user \'admin@example.com\' ';

    private UserPasswordEncoderInterface $passwordEncoder;

    private BackofficeUserRepository $backofficeUserRepository;

    /**
     * DefaultAdminCreateCommand constructor.
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, BackofficeUserRepository $backofficeUserRepository)
    {
        parent::__construct();
        $this->passwordEncoder = $passwordEncoder;
        $this->backofficeUserRepository = $backofficeUserRepository;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('password', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

//        $passwordArg = (string) $input->getArgument('password');

//        if ($passwordArg) {
//            $io->note(sprintf('You passed an argument: %s', $passwordArg));
//        }

//        if ($input->getOption('option1')) {
//            // ...
//        }

        $admin = new BackofficeUser();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordEncoder->encodePassword($admin, 'demo')
        );

        $this->backofficeUserRepository->save($admin);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
