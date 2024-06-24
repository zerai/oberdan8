<?php declare(strict_types=1);

namespace Booking\Adapter\Persistance;

use Booking\Application\Domain\UseCase\ExportDataRetrieverInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ExportDataRetriever implements ExportDataRetrieverInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Return all customers with email
     *
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getAllCustomerForNewsletter(): array
    {
        $conn = $this->entityManager->getConnection();

        $sql = '
            SELECT last_name, first_name, email FROM bkg_reservation r
            WHERE r.email <> \'\'
            ORDER BY r.last_name ASC
            ';
        try {
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery();
            return $resultSet->fetchAllAssociative();
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
