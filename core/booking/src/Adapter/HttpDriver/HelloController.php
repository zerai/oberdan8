<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Symfony\Component\HttpFoundation\Response;

class HelloController
{
    public function __invoke(): Response
    {
        return new Response(
            '<html><body>Hello by oberdan 8 !!!</body></html>'
        );
    }
}
