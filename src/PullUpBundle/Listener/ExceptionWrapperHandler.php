<?php

namespace PullUpBundle\Listener;

use FOS\RestBundle\View\ExceptionWrapperHandlerInterface;
use PullUpBundle\ParamConverter\ValidationException;

class ExceptionWrapperHandler implements ExceptionWrapperHandlerInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function wrap($data)
    {
        // we get the original exception
        /** @var \Exception $exception */
        $exception = $data['exception'];

        $response = [
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage(),
            'errors' => []
        ];

        if ($exception->getClass() === ValidationException::class) {
            $response['errors'] = json_decode($exception->getMessage(), true);
            $response['message'] = 'VALIDATION.ERROR';
        }

        return $response;
    }
}
