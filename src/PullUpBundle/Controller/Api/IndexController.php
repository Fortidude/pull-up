<?php

namespace PullUpBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\TwigBundle\TwigEngine;

class IndexController
{
    /**
     * @var TwigEngine
     */
    private $templating;

    public function __construct(TwigEngine $templating)
    {
        $this->templating = $templating;
    }

    /**
     * @Rest\View(statusCode=503, serializerGroups={})
     */
    public function indexAction()
    {
        return 'API';
    }
}
