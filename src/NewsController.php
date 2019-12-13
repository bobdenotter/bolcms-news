<?php


namespace App;

use App\Entity\Hits;
use App\Repository\HitsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Bolt\Controller\TwigAwareController;

class NewsController extends TwigAwareController
{
    /** @var EntityManager */
    private $entityManager;

    /** @var HitsRepository */
    private $hitsRepository;

    public function __construct(ObjectManager $entityManager, HitsRepository $hitsRepository)
    {
        $this->entityManager = $entityManager;
        $this->hitsRepository = $hitsRepository;
    }

    /**
     * @Route("/", methods={"GET"}, name="news")
     */
    public function index(Request $request)
    {
        $this->addHit($request);

        $response = $this->renderTemplate('json.twig', []);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    private function addHit(Request $request)
    {
        $options = unserialize(base64_decode($request->get('hash')));

        $oldHit = $this->hitsRepository->findOneBy({ })

        $options['remote'] = $request->getClientIp();

        $hit = new Hits();
        $hit
            ->setVersion($options['v'])
            ->setPhp($options['php'])
            ->setLocal($options['host'])
            ->setName($options['name'])
            ->setDbdriver($options['db_driver'])
            ->setDbversion($options['db_version'])
            ->setRemote($options['remote']);

        $this->entityManager->persist($hit);
        $this->entityManager->flush();
    }

}