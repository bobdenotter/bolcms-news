<?php

namespace App\Twig;

use App\Entity\Hits;
use App\Repository\HitsRepository;
use Bolt\Common\Str;
use Bolt\Twig\LocaleExtension;
use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Tightenco\Collect\Support\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class HitsExtension extends AbstractExtension
{
    public const CACHE_DURATION = 14400; // 4 hours

    /** @var HitsRepository */
    private $repository;

    /** @var LocaleExtension */
    private $localeExtension;

    /** @var Request */
    private $request;

    /** @var CacheInterface */
    private $cache;

    public function __construct(HitsRepository $repository, LocaleExtension $localeExtension, RequestStack $requestStack, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->localeExtension = $localeExtension;
        $this->request = $requestStack->getCurrentRequest();
        $this->cache = $cache;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('unique_hits', [$this, 'uniqueHits']),
            new TwigFilter('group_by_created_at', [$this, 'groupByCreatedAt']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('hits', [$this, 'getHits']),
        ];
    }

    public function getHits(string $since = '', ?string $groupBy = 'week'): array
    {
        $since = $this->request->get('since', '-3 months');
        $hits = $this->getCachedHits($since);

        switch($groupBy) {
            case 'week':
                return $this->groupByCreatedAt($hits, 'week');
            case 'dbdriver':
                return $this->groupByDBDriver($hits);
            case 'phpversion':
                return $this->groupByPHPVersion($hits);
            case 'boltversion':
                return $this->groupByBoltVersion($hits);
            case 'local':
                return $this->groupByLocal($hits);
        }

        return $hits;
    }

    public function uniqueHits(array $items, string $filter): array
    {
        $items = collect($items);

        return $items->unique(function(Hits $hit) use ($filter){
            return $hit->{$filter}();
        })->toArray();
    }

    private function getCachedHits(string $since): array
    {
        return $this->cache->get('hits_'.Str::slug($since), function(ItemInterface $item) use ($since) {
            $item->expiresAfter(self::CACHE_DURATION);
            $since = (new \DateTime)->modify($since);
            $startOfThisWeek = (new Carbon())->startOfWeek();
            $criteria = new Criteria();
            $criteria->where($criteria->expr()->gte('createdAt', $since));
            $criteria->andWhere($criteria->expr()->lte('createdAt', $startOfThisWeek));
            $criteria->orderBy(['createdAt' => 'ASC']);

            return $this->repository->matching($criteria)->toArray();
        });
    }

    private function groupByBoltVersion(array $items): array
    {
        $items = collect($items);

        return $items->groupBy(function(Hits $hit) {
            $version = $hit->getVersion();

            if(mb_strpos($version, 'RC')) {
                preg_match('/(RC \d+)$/', $version, $matches);
            } else {
                preg_match('/^(\d\.\d)/', $version, $matches);
            }

            return $matches[1];
        })->toArray();
    }

    private function groupByPHPVersion(array $items): array
    {
        $items = collect($items);

        return $items->groupBy(function(Hits $hit) {
            $version = $hit->getPhp();
            preg_match('/^(\d\.\d)/', $version, $matches);
            return $matches[1];
        })->toArray();
    }

    private function groupByDBDriver(array $items): array
    {
        $items = collect($items);

        return $items->groupBy(function(Hits $hit) {
            return $hit->getDbdriver();
        })->toArray();
    }

    private function groupByLocal(array $items): array
    {
        $items = collect($items);

        return $items->groupBy(function(Hits $hit) {
            return $hit->getLocal();
        })->toArray();
    }

    public function groupByCreatedAt(array $items, string $groupBy): array
    {
        $items = collect($items);

        return $items->groupBy(function(Hits $hit) use ($groupBy) {
            $createdAt = new Carbon($hit->getCreatedAt());
            if ($groupBy === 'week') {
                $startOfWeek = $createdAt->startOfWeek();
                return $this->localeExtension->localdate($startOfWeek);
            }

            return 0;
        })->toArray();
    }
}
