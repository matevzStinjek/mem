<?php

namespace App\Filters;

use App\Util\Util;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthFilter implements Filter {

    private $em;
    private $container;

    public function __construct(Container $container) {
        $this->em        = $container->get('em');
        $this->container = $container;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response {
        $user = $this->getUser($request);
        $this->container->set('user', $user);
        $response = $handler->handle($request);
        return $response;
    }

    private function getUser(Request $request) {

        // return null;
        [$email, $password] = explode(':', base64_decode(explode(' ', $request->getHeader('Authorization')[0])[1]));
        // $passwordHash = Util::hashPassword($password);

        $user = $this->em->createQueryBuilder()
            ->select('user')
            ->from('App\Model\Entities\User', 'user')
            ->andWhere('user.email = :email')->setParameter('email', $email)
            ->getQuery()->getOneOrNullResult();

        error_log(get_class($user));
    }
}