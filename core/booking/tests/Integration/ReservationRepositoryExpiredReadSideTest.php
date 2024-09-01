<?php declare(strict_types=1);


namespace Booking\Tests\Integration;

use App\Factory\ReservationFactory;
use App\Factory\ReservationSaleDetailFactory;
use Booking\Adapter\Persistence\ReservationRepository;
use Booking\Application\Domain\Model\Reservation;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/** @covers \Booking\Adapter\Persistence\ReservationRepository */
class ReservationRepositoryExpiredReadSideTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    private string $repositoryClass = Reservation::class;

    private ReservationRepository $repository;

    use ResetDatabase;

    use Factories;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->repository = $this->entityManager
            ->getRepository($this->repositoryClass);
    }

    /** @test */
    public function shouldCountReservationExpiredAfter7days(): void
    {
        self::createReservationExpiredInTheMorningOfSomeDayAgo(7);
        self::createReservationExpiredInTheAfternoonOfSomeDayAgo(7);

        // NOISE: CONFIRMED BUT NOT EXPIRED
        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmed1DayAgo(),
        ]);

        self::assertEquals(2, $this->repository->countWithStatusConfirmedAndExpired());
    }

    /** @test */
    public function shouldCountReservationExpiredAfter14days(): void
    {
        self::createReservationExpiredInTheMorningOfSomeDayAgo(14);
        self::createReservationExpiredInTheAfternoonOfSomeDayAgo(14);

        // NOISE DATA, CONFIRMED BUT NOT EXPIRED
        $noise = ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmed10DayAgo()->withExtensionTime(),
        ]);

        self::assertEquals(2, $this->repository->countWithStatusConfirmedAndExpired());
    }

    /** @test */
    public function shouldCountReservationExpiredAfter7daysAndExpiredAfter14Days(): void
    {
        self::createReservationExpiredInTheMorningOfSomeDayAgo(7);
        self::createReservationExpiredInTheAfternoonOfSomeDayAgo(7);

        self::createReservationExpiredInTheMorningOfSomeDayAgo(14);
        self::createReservationExpiredInTheAfternoonOfSomeDayAgo(14);

        // NOISE DATA, CONFIRMED BUT NOT EXPIRED
        ReservationFactory::createMany(1, [
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmed1DayAgo(),
        ]);

        ReservationFactory::createMany(1, [
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmed10DayAgo()
                ->withExtensionTime(),
        ]);

        self::assertEquals(6, \count($this->repository->findAll()));

        self::assertEquals(4, $this->repository->countWithStatusConfirmedAndExpired());
    }

    /** @test */
    public function shouldGetReservationExpiredAfter7days(): void
    {
        self::createReservationExpiredInTheMorningOfSomeDayAgo(7);
        self::createReservationExpiredInTheAfternoonOfSomeDayAgo(7);

        // NOISE: CONFIRMED BUT NOT EXPIRED
        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmed1DayAgo(),
        ]);
        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmed10DayAgo()
                ->withExtensionTime(),
        ]);

        $reservations = $this->repository->findWithQueryBuilderAllConfirmedAndExpiredOrderByOldest('');

        self::assertEquals(2, \count($reservations->getQuery()->getResult()));
    }

    /** @test */
    public function shouldGetReservationExpiredAfter14days(): void
    {
        self::createReservationExpiredInTheMorningOfSomeDayAgo(14);
        self::createReservationExpiredInTheAfternoonOfSomeDayAgo(14);

        // NOISE DATA, CONFIRMED BUT NOT EXPIRED
        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmed1DayAgo(),
        ]);
        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmed10DayAgo()
                ->withExtensionTime(),
        ]);

        $reservations = $this->repository->findWithQueryBuilderAllConfirmedAndExpiredOrderByOldest('');

        self::assertEquals(4, \count($this->repository->findAll()));

        self::assertEquals(2, \count($reservations->getQuery()->getResult()));
    }

    /** @test */
    public function shouldGetReservationExpiredAfter7daysAndExpiredAfter14Days(): void
    {
        self::createReservationExpiredInTheMorningOfSomeDayAgo(7);
        self::createReservationExpiredInTheAfternoonOfSomeDayAgo(7);

        self::createReservationExpiredInTheMorningOfSomeDayAgo(14);
        self::createReservationExpiredInTheAfternoonOfSomeDayAgo(14);

        // NOISE DATA, CONFIRMED BUT NOT EXPIRED
        ReservationFactory::createMany(1, [
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmed1DayAgo(),
        ]);
        ReservationFactory::createMany(1, [
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmed10DayAgo()
                ->withExtensionTime(),
        ]);

        $reservations = $this->repository->findWithQueryBuilderAllConfirmedAndExpiredOrderByOldest('');

        self::assertEquals(6, \count($this->repository->findAll()));

        self::assertEquals(4, \count($reservations->getQuery()->getResult()));
    }

    private function createReservationExpiredInTheMorningOfSomeDayAgo(int $dayAgo): void
    {
        $format = 'Y-m-d H:i:s';

        $dayAgo = $dayAgo + 1;

        $dayAgoAsString = \sprintf('- %ddays', $dayAgo);

        $confirmed_atDayAgo = (new DateTimeImmutable("today"))->modify($dayAgoAsString);

        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmationDate(
                DateTimeImmutable::createFromFormat($format, $confirmed_atDayAgo->format('Y-m-d') . ' 08:15:00', new DateTimeZone('Europe/Rome'))
            ),
        ]);
    }

    public function createReservationExpiredInTheAfternoonOfSomeDayAgo(int $dayAgo): void
    {
        $format = 'Y-m-d H:i:s';

        $dayAgo = $dayAgo + 1;

        $dayAgoAsString = \sprintf('- %ddays', $dayAgo);

        $confirmed_atDayAgo = (new DateTimeImmutable("today"))->modify($dayAgoAsString);

        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmationDate(
                DateTimeImmutable::createFromFormat($format, $confirmed_atDayAgo->format('Y-m-d') . ' 18:15:00', new DateTimeZone('Europe/Rome'))
            ),
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
