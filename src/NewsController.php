<?php


namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Bolt\Controller\TwigAwareController;

class NewsController extends TwigAwareController
{

    /**
     * @Route("/", methods={"GET"}, name="news")
     */
    public function index(Request $request)
    {
        $options = unserialize(base64_decode($request->get('hash')));

        $options['remote'] = $request->getClientIp();

        dump($options);



        return new Response('hoi');
    }
}