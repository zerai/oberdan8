<?php declare(strict_types=1);

namespace App\Command;

use Booking\Adapter\Persistance\ReservationRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FixPackageIdCommand extends Command
{
    protected static $defaultName = 'app:fix:packageid';

    protected static string $defaultDescription = 'Fix packageId column ';

    private ReservationRepository $reservationRepository;

    /**
     * DefaultAdminCreateCommand constructor.
     */
    public function __construct(ReservationRepository $reservationRepository)
    {
        parent::__construct();

        $this->reservationRepository = $reservationRepository;
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

        $passwordArg = $input->getArgument('password');

        if ($passwordArg) {
            $io->note(sprintf('You passed an argument: %s', $passwordArg));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $allRecords = $this->reservationRepository->findAll();

        foreach ($allRecords as $reservation) {

            //$reservation
//                $io->success(
//                    sprintf('working on: %s', $reservation->getId()->toString())
//                );

            if ($reservation->getSaleDetail()->getReservationPackageId() === null || $reservation->getSaleDetail()->getReservationPackageId() === '') {
                $io->success(
                    sprintf('working on: %s - current packageId %s', $reservation->getId()->toString(), $reservation->getSaleDetail()->getReservationPackageId())
                );

                $reservation->getSaleDetail()->setReservationPackageId(
                    $reservation->getFirstName()
                );

                $io->success(
                    sprintf('Saving reservation: %s with package Id: %s', $reservation->getId()->toString(), $reservation->getSaleDetail()->getReservationPackageId())
                );

                $this->reservationRepository->save($reservation);
            }
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
