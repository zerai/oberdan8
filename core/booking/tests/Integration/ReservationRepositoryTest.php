<?php declare(strict_types=1);


namespace Booking\Tests\Integration;

use Booking\Application\Domain\Model\Book;
use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationSeleDetail;
use Booking\Application\Domain\Model\ReservationStatus;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/** @covers \Booking\Adapter\Persistance\ReservationRepository */
class ReservationRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private string $repositoryClass = Reservation::class;

    private $repository;

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
    public function it_can_save_and_retrieve_an_entity(): void
    {
        $originalEntity = $this->createEntity();
        $this->repository->save($originalEntity);

        $entityFromDatabase = $this->repository->withId($originalEntity->getId());

        self::assertEquals($originalEntity, $entityFromDatabase);
    }

    /** @test */
    public function it_can_save_child_entities(): void
    {
        $originalEntity = $this->createEntityWithChild();
        $this->repository->save($originalEntity);

        // Now load it from the database
        $entityFromDatabase = $this->repository->find($originalEntity->getId());

        // Compare it to the entity as we've set it up for this test
        self::assertEquals($originalEntity, $entityFromDatabase);
    }

    /** @test */
    public function it_can_save_child_entities_updates(): void
    {
        // Create a basic version of the entity and store it
        $originalEntity = $this->createEntityWithChild();
        $this->repository->save($originalEntity);

        // Load and save again, now with an added child entity
        $originalEntity = $this->repository->find($originalEntity->getId());
        $firstChild = new Book();
        $firstChild->setTitle('foo title');
        $originalEntity->addBook($firstChild);
        $this->repository->save($originalEntity);

        // Now load it from the database
        $entityFromDatabase = $this->repository->find($originalEntity->getId());

        // Compare it to the entity as we've set it up for this test
        self::assertEquals($originalEntity, $entityFromDatabase);
    }

    /** @test */
    public function it_can_delete_an_entity(): void
    {
        self::markTestIncomplete();
        // Create the entity
        $originalEntity = $this->createEntityWithChild();
        $this->repository->save($originalEntity);

        // Create another entity
        $anotherEntity = $this->createEntityWithChild();
        $this->repository->save($anotherEntity);

        // Now delete that other entity
        $this->repository->delete($anotherEntity);

        // Verify that the first entity still exists
        $entityFromDatabase = $this->repository->find($originalEntity->getId());
        self::assertEquals($originalEntity, $entityFromDatabase);

        // Verify that the second entity we just removed, can't be found
        $this->expectException(EntityNotFoundException::class);
        $this->repository->find($anotherEntity->getId());
    }

    private function createEntity(): Reservation
    {
        $itemDetail = new ReservationSeleDetail();
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
