<?php

namespace PullUpBundle\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter as ParamConverterConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use JMS\Serializer\GenericDeserializationVisitor;

use JMS\Serializer\Serializer;

/**
 * Class ParamConverter
 * @package Fachowo\ApiBundle\ParamConverter
 */
class DataValidConverter implements ParamConverterInterface
{
    /** @var Serializer */
    private $serializer;

    /** @var */
    private $validator;

    /**
     * ParamConverter constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer, $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @param ParamConverterConfig $configuration
     * @return bool
     */
    public function supports(ParamConverterConfig $configuration)
    {
        if (!$configuration->getClass()) {
            return false;
        }

        return true;
    }

    /**
     * @param Request $request
     * @param ParamConverterConfig $configuration
     * @return void
     */
    public function apply(Request $request, ParamConverterConfig $configuration)
    {
        $class = $configuration->getClass();
      //  try {
            $object = $this->serializer->deserialize(
                json_encode($request->request->all()),
                $class,
                'json'
            );


        // set the object as the request attribute with the given name
        // (this will later be an argument for the action)
        $request->attributes->set($configuration->getName(), $object);
        $errors = $this->validator->validate($object);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}
