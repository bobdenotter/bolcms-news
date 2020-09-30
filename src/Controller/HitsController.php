<?php

namespace App\Controller;

use Bolt\Controller\Backend\BackendZoneInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class HitsController extends AbstractController implements BackendZoneInterface
{
    /**
     * @Route("/stats", name="app_stats", methods={"GET"})
     */
    public function stats(): Response
    {
        return $this->render('@app/stats.twig');
    }
}
