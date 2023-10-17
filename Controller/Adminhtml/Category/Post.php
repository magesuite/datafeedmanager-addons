<?php
declare(strict_types=1);

namespace MageSuite\DataFeedManagerAddons\Controller\Adminhtml\Category;

class Post extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Wyomind_DataFeedManager::feeds';

    protected \MageSuite\DataFeedManagerAddons\Model\ResourceModel\CategoryCopier $categoryCopier;

    public function __construct(
        \MageSuite\DataFeedManagerAddons\Model\ResourceModel\CategoryCopier $categoryCopier,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->categoryCopier = $categoryCopier;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $sourceId = (int)$this->getRequest()->getParam('source_id');
        $targetId = (int)$this->getRequest()->getParam('target_id');
        $this->categoryCopier->copy($sourceId, $targetId);

        return $resultRedirect->setPath('datafeedmanager/feeds/index');
    }
}
