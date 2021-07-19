<?php declare(strict_types=1);

namespace App\Controller;

use Booking\Adapter\MailDriven\BookingMailer;
use Booking\Application\Domain\Model\ReservationRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/admin/mailer")
 */
class BackofficeMailManagerController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_mailer_manager_index", methods={"GET"})
     */
    public function index(ReservationRepositoryInterface $repository): Response
    {
        return $this->render('backoffice/mail-manager/index.html.twig', [//'backoffice_reservations' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/send-example/thanks-mail", name="backoffice_mailer_manager_example_send_tanks_mail", methods={"GET"})
     */
    public function sendThanksMailTemplate(Request $request, UserInterface $user, MailerInterface $mailer, BookingMailer $bookingMailer): Response
    {
        $bookingMailer->notifyReservationThanksEmailToClient($user->getUsername(), '');

        $this->addFlash('success', 'Email template inviato all\'indirizzo: ' . $user->getUsername());

        return $this->redirectToRoute('backoffice_mailer_manager_index');
    }
}
