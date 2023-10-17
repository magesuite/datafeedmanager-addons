<?php
declare(strict_types=1);

namespace MageSuite\DataFeedManagerAddons\Controller\Adminhtml\Category;

class Index extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Wyomind_DataFeedManager::feeds';

    public function execute()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $title = __('Category Transfer');
        $resultPage->setActiveMenu('Wyomind_DataFeedManager::feeds');
        $resultPage->addBreadcrumb($title, $title);
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }
}
