<?php

namespace PullUpBundle\ParamConverter;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{
    protected $validationErrors;

    public function __construct(ConstraintViolationListInterface $validationErrors, $code = 400, \Exception $previous = null)
    {
        $this->validationErrors = $validationErrors;
        parent::__construct($this->getErrors(), $code, $previous);
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    public function getErrors()
    {
        $errors = [];
        foreach ($this->validationErrors as $error) {
            $errors[$error->getPropertyPath()] = $error->getMessage();
        }

        return json_encode($errors);
    }
}
