<?php

namespace Aizetech\PageCacheWarm\Controller\Adminhtml\WarmCache;

use Aizetech\PageCacheWarm\Model\PageCacheWarmer;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{
    protected PageCacheWarmer $pageCacheWarmer;

    public function __construct(
        Context         $context,
        PageCacheWarmer $pageCacheWarmer
    )
    {
        parent::__construct($context);
        $this->pageCacheWarmer = $pageCacheWarmer;
    }

    public function execute()
    {
        if ($this->pageCacheWarmer->warmPageCache()) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath('admin/system_config/edit/section/page_cache_warm');
            $this->messageManager->addSuccessMessage(__('Page cache has been warmed successfully.'));
            return $resultRedirect;
        }
        $this->messageManager->addErrorMessage(__('Something went wrong, try again later.'));
    }
}
