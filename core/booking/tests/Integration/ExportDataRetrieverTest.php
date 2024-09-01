<?php declare(strict_types=1);

namespace Booking\Tests\Integration;

use App\Factory\ReservationFactory;
use Booking\Adapter\Persistence\ExportDataRetriever;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @covers \Booking\Adapter\Persistence\ExportDataRetriever
 */
class ExportDataRetrieverTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    private ?ExportDataRetriever $dataRetriever;

    use ResetDatabase;

    use Factories;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->dataRetriever = new ExportDataRetriever($this->entityManager);
    }

    /** @test */
    public function shouldreturnAnEmptyArray(): void
    {
        $result = $this->dataRetriever->getAllCustomerForNewsletter();

        self::assertEquals(0, \count($result), 'Expected empty resulset.');
    }

    /** @test */
    public function shouldExcludeCustomersWithoutEmail(): void
    {
        ReservationFactory::new()->create();
        ReservationFactory::new()
            ->withConfirmedStatus()
            ->withAttributes([
                'email' => '',
            ])
            ->create();

        $result = $this->dataRetriever->getAllCustomerForNewsletter();

        self::assertEquals(1, \count($result), 'Expected 1 record, record without email should be excluded.');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
        $this->dataRetriever = null;
    }
}
