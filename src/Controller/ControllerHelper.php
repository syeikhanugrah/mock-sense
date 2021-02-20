<?php

namespace App\Controller;

use Doctrine\Persistence\ObjectManager;

trait ControllerHelper
{
    public function getEntityManager(): ObjectManager
    {
        return $this->getDoctrine()->getManager();
    }
}
