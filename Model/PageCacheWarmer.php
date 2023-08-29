<?php

namespace Aizetech\PageCacheWarm\Model;
use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;


class PageCacheWarmer
{
    protected $scopeConfig;
    protected $logger;
    protected Curl $curl;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Curl                                               $curl,
        LoggerInterface                                    $logger
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->curl = $curl;
        $this->logger = $logger;
    }
    public function warmPageCache():bool
    {

        return $this->visitCriticalPages();


    }

    /**
     * Function to visit critical pages and trigger cache regeneration
     */
    protected function visitCriticalPages(): bool
    {
        $tryCount = 0;
        $resultArray = [];
        $errorList = [];
        $urls = $this->getCriticalPageUrls();
        if (!empty($urls))
        {
        foreach ($urls as $url) {
            $resultArray[$url] = $this->makeCurlRequest(trim(rtrim($url)));
            if ($resultArray[$url] === false) {
                $errorList[$url] = ['url' => $url, 'is_warmed' => false];
            }
        }
        if (!empty($errorList)) {
            $this->logger->error('Page Cache Warmer errored when warming cache', [$errorList]);
            return false;
        }
        return true;
    }
        return false;
    }
    protected function makeCurlRequest($url):bool
    {
        try {
            $this->curl->setOption(CURLOPT_URL, trim($url));
            $this->curl->setOption(CURLOPT_VERBOSE, true);
            $this->curl->setOption(CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.63 Safari/537.36');
            $this->curl->setOption(CURLOPT_FOLLOWLOCATION, true);
            $this->curl->setOption(CURLOPT_HEADER, 0);
            $this->curl->setOption(CURLOPT_TIMEOUT, 60);
            $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
            $this->curl->setOption(CURLOPT_CUSTOMREQUEST, 'GET');
            $this->curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $this->curl->setOption(CURLOPT_SSL_VERIFYHOST, false);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->get($url);
            $response = $this->curl->getBody();

            if (!empty($response) && $this->curl->getStatus() === 200) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return false;
    }

    /**
     * Get the list of critical page URLs from system configuration
     */
    protected function getCriticalPageUrls()
    {
        $configUrls = $this->scopeConfig->getValue('page_cache_warm/urls', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!empty($configUrls)) {
            $configUrls = $configUrls['url_list'];
            return explode("\n", $configUrls);
        }
        return false;
    }
}
