<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\InfoBoxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoBoxController extends AbstractController
{
    /**
     * @Route("/info-box", name="info_box_show", methods={"GET"})
     */
    public function __invoke(InfoBoxRepository $infoBoxRepository): Response
    {
        return $this->render('info-box/_index.html.twig', [
            'info_box' => $infoBoxRepository->findDefaultInfoBox(),
        ]);
    }
}
