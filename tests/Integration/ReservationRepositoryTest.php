<?php declare(strict_types=1);


namespace App\Tests\Integration;

use App\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReservationRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCanSave(): void
    {
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
        ;

        $this->entityManager
            ->getRepository(Reservation::class)
            ->save($item)
            //->findOneBy(['name' => 'Priceless widget'])
        ;

        $itemFromDb = $this->entityManager
            ->getRepository(Reservation::class)

            ->findOneBy([
                'email' => 'foo@example.com',
            ])
        ;

        //$this->assertSame(14.50, $product->getPrice());
        //dd($itemFromDb);
        $this->assertNotNull($itemFromDb); //Equals($item, $itemFromDb);
    }

    public function it_can_save_and_retrieve_an_entity(): void
    {
        self::markTestIncomplete();
        // Create a basic version of the entity and store it
        //$originalEntity = ...;
        $this->repository->save($originalEntity);

        // Now load it from the database
        $entityFromDatabase = $this->repository->getById($originalEntity->entityId());

        // Compare it to the entity you created for this test
        self::assertEquals($originalEntity, $entityFromDatabase);
    }

    public function it_can_save_child_entities(): void
    {
        self::markTestIncomplete();
        // Create a basic version of the entity and store it
        //    $originalEntity = ...;
        // Add some child entity
        //$originalEntity->addChildEntity(...);
        $this->repository->save($originalEntity);

        // Now load it from the database
        $entityFromDatabase = $this->repository->getById($originalEntity->entityId());

        // Compare it to the entity as we've set it up for this test
        self::assertEquals($originalEntity, $entityFromDatabase);
    }

    public function it_can_save_child_entities2(): void
    {
        self::markTestIncomplete();
        // Create a basic version of the entity and store it
        //$originalEntity = ...;
        $this->repository->save($originalEntity);
        // Load and save again, now with an added child entity
        $originalEntity = $this->repository->getById($originalEntity->entityId());
        //$originalEntity->addChildEntity(...);
        $this->repository->save($originalEntity);

        // Now load it from the database
        $entityFromDatabase = $this->repository->getById($originalEntity->entityId());

        // Compare it to the entity as we've set it up for this test
        self::assertEquals($originalEntity, $entityFromDatabase);
    }

    public function it_can_delete_an_entity(): void
    {
        self::markTestIncomplete();
        // Create the entity
        //$originalEntity = ...;
        //$originalEntity->addChildEntity(...);
        $this->repository->save($originalEntity);

        // Create another entity
        //$anotherEntity = ...;
        //$anotherEntity->addChildEntity(...);
        $this->repository->save($anotherEntity);

        // Now delete that other entity
        $this->repository->delete($anotherEntity);

        // Verify that the first entity still exists
        $entityFromDatabase = $this->repository->getById($originalEntity->entityId());
        self::assertEquals($originalEntity, $entityFromDatabase);

        // Verify that the second entity we just removed, can't be found
        //$this->expectException(EntityNotFound::class);
        $this->repository->getById($anotherEntity->entityId());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
