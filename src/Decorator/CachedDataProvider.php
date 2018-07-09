<?php
/**
 * Copyright (c) 2017 Alexander V. Basyrov, basyrov.av@gmail.com
 * Date: 10.07.18
 * Time: 1:52
 */
declare(strict_types=1);

namespace src\Decorator;

use Psr\Log\LoggerInterface;
use Psr\Cache\CacheItemPoolInterface;
use src\SomeLockService;
use src\Integration\DataProvider\Reply;
use src\Integration\DataProvider\Request;
use src\Integration\DataProvider\ApiInterface as DataProviderInterface;

class CachedDataProvider implements DataProviderInterface
{
    private const LOCK_TIME = 2;
    private const CACHE_TTL = 86400;    // seconds, 86400 - 1 day

    private $cache;
    private $logger;
    private $locker;
    private $dataProvider;

    /**
     * @param DataProviderInterface $dataProvider
     * @param CacheItemPoolInterface $cache
     * @param SomeLockService $locker
     * @param LoggerInterface $logger
     */
    public function __construct(
        DataProviderInterface $dataProvider,
        CacheItemPoolInterface $cache,
        SomeLockService $locker,
        LoggerInterface $logger
    ) {
        $this->cache        = $cache;
        $this->logger       = $logger;
        $this->locker       = $locker;
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param Request $requestParams
     * @return Reply
     * @throws \Exception
     */
    public function request(Request $requestParams): Reply
    {
        $cacheKey = $this->getCacheKey($requestParams);

        // Блокировка обоснована в случае соблюдения всех следующих условий:
        // 1) доступ к внешнему сервису довольно долгий
        // 2) cache hit rate хороший
        // 3) ожидается большое число вызовов текущего метода
        // 4) но не настолько большое, чтобы блокировки нам сильно мешали
        //
        // Без блокировки периодически будет возникать ситуация,
        // когда несколько параллельных потоков получили cache miss
        // и кинулись с одним и тем же запросом в внешнему сервису.
        //
        // При совсем больших нагрузках, можно избегать вышеописанной
        // ситуации и без блокировок, более оптимальными способами.
        $lock = $this->locker->acquire(self::LOCK_TIME);

        try {
            $cacheItem = $this->cache->getItem($cacheKey);

            if ($cacheItem->isHit()) {
                $lock->unlock();  // Высвобождаем блокировку как можно раньше

                $result = $cacheItem->get();
            } else {
                $result = $this->dataProvider->request($requestParams);

                $cacheItem->set($result)->expiresAfter(self::CACHE_TTL);
                $this->cache->save($cacheItem);

                $lock->unlock();
            }
        } catch (\Exception $e) {
            $lock->unlock();
            $this->logger->critical('Unexpected error occurred: ' . $e->getMessage());
            throw $e;
        }

        return $result;
    }

    private function getCacheKey(Request $requestParams)
    {
        // По хорошему, нужен еще и namespace для кэша,
        // но это должно решаться системно
        return sha1(serialize($requestParams));
    }
}
