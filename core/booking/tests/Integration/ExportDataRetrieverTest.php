<?php declare(strict_types=1);

namespace Booking\Tests\Integration;

use App\Factory\ReservationFactory;
use Booking\Adapter\Persistance\ExportDataRetriever;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ExportDataRetrieverTest extends KernelTestCase
{
//    private EntityManager $entityManager;

    private ExportDataRetriever $dataRetriever;

    use ResetDatabase;

    use Factories;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

//        $this->entityManager = $kernel->getContainer()
//            ->get('doctrine')
//            ->getManager();

//        $this->dataRetriever = new ExportDataRetriever($this->entityManager);
        $this->dataRetriever = $kernel->getContainer()
            ->get('\Booking\Adapter\Persistance\ExportDataRetriever')
            ->getManager();

    }

    /** @test */
    public function shouldExcludeCustomersWithoutEmail(): void
    {
        ReservationFactory::new()->create();
        //ReservationFactory::new()->withConfirmedStatus()->create();
        //ReservationFactory::new()->withConfirmedStatus()->create();

        $result = $this->dataRetriever->getAllCustomerForNewsletter();

        self::assertEquals(1, \count($result));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
        $this->dataRetriever = null;
    }
}
