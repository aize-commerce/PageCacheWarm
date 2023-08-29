<?php
namespace Aizetech\PageCacheWarm\Observer;

use Aizetech\PageCacheWarm\Model\PageCacheWarmer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class CacheWarmer implements ObserverInterface
{
    public function __construct(
        PageCacheWarmer $pageCacheWarmer,
        LoggerInterface $logger
    )
    {
        $this->pageCacheWarmer = $pageCacheWarmer;
        $this->logger = $logger;
    }
    public function execute(Observer $observer)
    {
        $result = $this->pageCacheWarmer->warmPageCache();

        if($result) {
            $this->logger->info('Cache warmer observer successfully completed.', ['observer_data' => $observer->getData()]);
        }
        else
        {
            $this->logger->info('Cache warmer observer can not completed.', ['observer_data' => $observer->getData()]);
        }
    }
}
