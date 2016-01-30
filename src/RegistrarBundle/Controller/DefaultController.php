<?php

namespace RegistrarBundle\Controller;

use RegistrarBundle\RegistrationExecutor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function createAction(Request $request)
    {
        $data = array(
            'status' => 'ok',
        );

        /** @var RegistrationExecutor $registrationExecutor */
        $registrationExecutor = $this->get('registration_executor');

        $email = $request->get('email');

        try {
            $registrationExecutor->handleEmail($email);
        } catch (\Exception $ex) {
            $data['status'] = 'error';
            $data['error'] = $ex->getMessage();
        }

        return new JsonResponse($data);
    }

    public function validateAction(Request $request)
    {
        $ticket = $request->get('ticket');
        $email = $request->get('email');

        /** @var RegistrationExecutor $registrationExecutor */
        $registrationExecutor = $this->get('registration_executor');

        $data = [
            'email'  => $email,
        ];

        $result = false;
        try {
            $result = $registrationExecutor->validate($ticket, $email);
        } catch (\Exception $ex) {
            $data['error'] = $ex->getMessage();
        }

        $data['status'] = $result ? 'ok' : 'error';

        return new JsonResponse($data);
    }
}
