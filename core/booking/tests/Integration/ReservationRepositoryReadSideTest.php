<?php declare(strict_types=1);


namespace Booking\Tests\Integration;

use App\Factory\ReservationFactory;
use Booking\Adapter\Persistance\ReservationRepository;
use Booking\Application\Domain\Model\Book;
use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationSaleDetail;
use Booking\Application\Domain\Model\ReservationStatus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/** @covers \Booking\Adapter\Persistance\ReservationRepository */
class ReservationRepositoryReadSideTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
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
        $todayDate = new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'));
        $twoDayAgoDate = (new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome')))
            ->modify('- 2 days');
        $threeDayAgoDate = (new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome')))
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
        $itemDetail->setStatus(ReservationStatus::NewArrival());

        $item = new Reservation();
        $item->setFirstName('foo')
            ->setLastName('foo')
            ->setEmail('foo@example.com')
            ->setPhone('06455858')
            ->setCity('foo')
            ->setClasse('prima')
            ->setRegistrationDate(
                new \DateTimeImmutable("now")
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

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
