<?php

namespace PullUpBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

class IndexController
{
    /**
     * @Rest\View(statusCode=503, serializerGroups={})
     */
    public function indexAction()
    {
        return 'API';
    }
}