<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Admin;

use Booking\Adapter\MailDriven\BookingMailer;
use Booking\Application\Domain\Model\ReservationRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route(path: '/admin/mailer')]
class BackofficeMailManagerController extends AbstractController
{
    #[Route(path: '/', name: 'backoffice_mailer_manager_index', methods: ['GET'])]
    public function index(ReservationRepositoryInterface $repository): Response
    {
        return $this->render('backoffice/mail-manager/index.html.twig', []);
    }

    #[Route(path: '/send-example/thanks-mail', name: 'backoffice_mailer_manager_example_send_tanks_mail', methods: ['GET'])]
    public function sendThanksMailTemplate(UserInterface $user, BookingMailer $bookingMailer): Response
    {
        $bookingMailer->notifyReservationThanksEmailToClient($user->getUserIdentifier(), '');

        //$this->addFlash('success', 'Email template inviato all\'indirizzo: ' . $user->getUsername());
        $this->addFlash('success', 'Email template inviato all\'indirizzo: ' . $user->getUserIdentifier());

        return $this->redirectToRoute('backoffice_mailer_manager_index');
    }
}
