<?php

namespace App\Filters;

use App\Model\Entities\UnregisteredUser;
use App\Exceptions\UserException;
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
        [$email, $password] = explode(':', base64_decode(explode(' ', $request->getHeader('Authorization')[0])[1]));

        $user = $this->em->createQueryBuilder()
            ->select('registeredUser')
            ->from('App\Model\Entities\RegisteredUser', 'registeredUser')
            ->andWhere('registeredUser.email = :email')->setParameter('email', $email)
            ->getQuery()->getOneOrNullResult();

        if (!is_null($user)) {
            if (!$user->isPasswordCorrect($password))
                throw new UserException('Invalid password');

            return $user;
        }

        return new UnregisteredUser;
    }
}
