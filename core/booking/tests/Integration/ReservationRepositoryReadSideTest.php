<?php declare(strict_types=1);


namespace Booking\Tests\Integration;

use App\Factory\ReservationFactory;
use App\Factory\ReservationSaleDetailFactory;
use Booking\Adapter\Persistence\ReservationRepository;
use Booking\Application\Domain\Model\Book;
use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationSaleDetail;
use Booking\Application\Domain\Model\ReservationStatus;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/** @covers \Booking\Adapter\Persistence\ReservationRepository */
class ReservationRepositoryReadSideTest extends KernelTestCase
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
    public function shouldGetAllReservationByStatus(string $status = 'NewArrival'): void
    {
        // default status is NewArrival
        ReservationFactory::createMany(5);

        $reservation = $this->repository->findAllNewArrivalOrderByNewest();

        self::assertEquals(5, \count($reservation));
    }

    /** @test */
    public function shouldGetAllReservationWithStatusConfirmed(): void
    {
        ReservationFactory::new()->withConfirmedStatus()->create();
        ReservationFactory::new()->withConfirmedStatus()->create();
        ReservationFactory::new()->withConfirmedStatus()->create();

        $reservation = $this->repository->findAllConfirmedOrderByOldest();

        self::assertEquals(3, \count($reservation));
    }

    /** @test */
    public function shouldGetAllReservationWithStatusConfirmedOrderedByOldestDate(): void
    {
        $todayDate = new DateTimeImmutable("now", new DateTimeZone('Europe/Rome'));
        $twoDayAgoDate = (new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))
            ->modify('- 2 days');
        $threeDayAgoDate = (new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))
            ->modify('- 3 days');

        ReservationFactory::new()
            ->withConfirmedStatus()
            ->withAttributes([
                'registrationDate' => $todayDate,
            ])
            ->create();
        ReservationFactory::new()
            ->withConfirmedStatus()
            ->withAttributes([
                'registrationDate' => $twoDayAgoDate,
            ])
            ->create();
        ReservationFactory::new()
            ->withConfirmedStatus()
            ->withAttributes([
                'registrationDate' => $threeDayAgoDate,
            ])
            ->create();

        $reservations = $this->repository->findAllConfirmedOrderByOldest();

        self::assertEquals($threeDayAgoDate, $reservations[0]->getRegistrationDate());
        self::assertEquals($twoDayAgoDate, $reservations[1]->getRegistrationDate());
        self::assertEquals($todayDate, $reservations[2]->getRegistrationDate());
    }

    private function createEntity(): Reservation
    {
        $itemDetail = new ReservationSaleDetail();
        $itemDetail->setStatus(ReservationStatus::newArrival());

        $item = new Reservation();
        $item->setFirstName('foo')
            ->setLastName('foo')
            ->setEmail('foo@example.com')
            ->setPhone('06455858')
            ->setCity('foo')
            ->setClasse('prima')
            ->setRegistrationDate(
                new DateTimeImmutable("now")
            )
            ->setSaleDetail(
                $itemDetail
            )
        ;

        return $item;
    }

    private function createEntityWithChild(): Reservation
    {
        $reservation = $this->createEntity();

        $firstChild = new Book();
        $firstChild->setTitle('foo title');

        $reservation->addBook($firstChild);

        return $reservation;
    }

    /** @test */
    public function shouldCountReservationWithStatusNewArrival(): void
    {
        ReservationFactory::createMany(10, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::newArrival(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::confirmed(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::pending(),
            ]),
        ]);

        $count = $this->repository->countWithStatusNewArrival();

        self::assertEquals(10, $count);
    }

    /** @test */
    public function shouldCountReservationWithStatusInProgress(): void
    {
        ReservationFactory::createMany(10, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::inProgress(),
            ]),
        ]);

        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::newArrival(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::confirmed(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::pending(),
            ]),
        ]);

        $count = $this->repository->countWithStatusInProgress();

        self::assertEquals(10, $count);
    }

    /** @test */
    public function shouldCountReservationWithStatusInPending(): void
    {
        ReservationFactory::createMany(10, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::pending(),
            ]),
        ]);

        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::newArrival(),
            ]),
        ]);

        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::inProgress(),
            ]),
        ]);

        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::confirmed(),
            ]),
        ]);

        $count = $this->repository->countWithStatusPending();

        self::assertEquals(10, $count);
    }

    /** @test */
    public function shouldCountReservationWithStatusRejected(): void
    {
        ReservationFactory::createMany(10, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::rejected(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::newArrival(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::pending(),
            ]),
        ]);

        $count = $this->repository->countWithStatusRejected();

        self::assertEquals(10, $count);
    }

    /** @test */
    public function shouldCountReservationWithStatusSale(): void
    {
        ReservationFactory::createMany(10, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::sale(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::newArrival(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::pending(),
            ]),
        ]);

        $count = $this->repository->countWithStatusSale();

        self::assertEquals(10, $count);
    }

    /** @test */
    public function shouldCountReservationWithStatusPickedUp(): void
    {
        ReservationFactory::createMany(10, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::pickedUp(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::newArrival(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::pending(),
            ]),
        ]);

        $count = $this->repository->countWithStatusPickedUp();

        self::assertEquals(10, $count);
    }

    /** @test */
    public function shouldCountReservationWithStatusBlacklist(): void
    {
        ReservationFactory::createMany(10, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::blacklist(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::newArrival(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::pending(),
            ]),
        ]);

        $count = $this->repository->countWithStatusBlacklist();

        self::assertEquals(10, $count);
    }

    /** @test */
    public function shouldCountReservationWithConfirmedStatus(): void
    {
        ReservationFactory::createMany(10, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::confirmed(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::newArrival(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::pending(),
            ]),
        ]);

        $count = $this->repository->countWithStatusConfirmed();

        self::assertEquals(10, $count);
    }

    /** @test */
    public function shouldCountReservationWithShippedStatus(): void
    {
        ReservationFactory::createMany(10, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::Shipped(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::newArrival(),
            ]),
        ]);
        ReservationFactory::createMany(15, [
            'saleDetail' => ReservationSaleDetailFactory::new([
                'status' => ReservationStatus::pending(),
            ]),
        ]);

        $count = $this->repository->countWithStatusShipped();

        self::assertEquals(10, $count);
    }

    /** @test */
    public function shouldGetAllReservationWithCouponCode(): void
    {
        ReservationFactory::new()->withCouponCode('foo')->create();
        ReservationFactory::new()->withCouponCode('bar')->create();
        ReservationFactory::new()->withCouponCode('foo bar')->create();

        $reservationQueryBuilder = $this->repository->findWithQueryBuilderAllWithCouponCodeOrderByNewest('');

        $reservation = $reservationQueryBuilder->getQuery()->getResult();

        self::assertEquals(3, \count($reservation));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
