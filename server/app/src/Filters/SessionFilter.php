<?php

namespace App\Filters;

use App\Model\Entities\Session;
use Dflydev\FigCookies\Cookies;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\SetCookies;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SessionFilter implements Filter {

    private $container;
    private $em;
    private $cookieName;

    public function __construct(Container $container) {
        $this->container  = $container;
        $this->em         = $container->get('em');
        $this->cookieName = $container->get('config')->cookieName;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response {
        $cookie = FigRequestCookies::get($request, $this->cookieName);

        $session = $this->em->getRepository('App\Model\Entities\Session')->findOneById($cookie->value) ?? new Session;
        $this->container->set('session', $session);
        $response = $handler->handle($request);

        // $setCookies = SetCookies::fromResponse($response);
        return $response;
    }
}
