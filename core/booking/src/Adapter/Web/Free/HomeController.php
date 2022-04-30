<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Free;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="app_home", methods="GET")
 */
class HomeController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('Static/home.html.twig', [
            'page' => 'homepage',
        ]);
    }
}
