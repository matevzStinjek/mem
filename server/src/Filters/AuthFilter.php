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
        $authHeader = $request->getHeader('Authorization');
        if (!$authHeader) {
            return new UnregisteredUser;
        }

        [$username, $secret] = explode(':', base64_decode(explode(' ', $authHeader[0])[1]));

        if (!$username) {
            return new UnregisteredUser;
        }

        if ($email = filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $user = $this->em->getRepository('App\Model\Entities\RegisteredUser')->findOneByEmail($email);
        } else {
            throw new UserException('You have provided an invalid set of credentials.');
        }

        if (is_null($user)) {
            throw new UserException("User with email '$email' not found");
        }
        if (!$user->isPasswordCorrect($secret)) {
            throw new UserException("Invalid password for $email");
        }

        return $user;
    }
}
