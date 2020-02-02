<?php

namespace App\Resources;

use App\Http\Request;
use App\Exceptions\IllegalArgumentException;
use App\Exceptions\UserException;
use App\Model\Entities\Session;

class SessionResource extends AbstractResource {

    public function create(Request $request) {
        $entity = $request->body;
        if (empty($entity->email)) {
            throw new IllegalArgumentException('Email is required!');
        }
        if (empty($entity->password)) {
            throw new IllegalArgumentException('Password is required!');
        }

        $user = $this->em->getRepository('App\Model\Entities\RegisteredUser')->findOneByEmail($entity->email);
        if (empty($user)) {
            throw new UserException("User with email '$entity->email' not found");
        }
        if (!$user->isPasswordCorrect($entity->password)) {
            throw new UserException("Invalid password for $entity->email");
        }

        $session = new Session($user);

        $this->em->persist($session);
        $this->em->flush();

        return $session;
    }
}