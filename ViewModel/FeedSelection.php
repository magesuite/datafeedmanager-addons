<?php
declare(strict_types=1);

namespace MageSuite\DataFeedManagerAddons\ViewModel;

class FeedSelection implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected \Wyomind\DataFeedManager\Model\ResourceModel\Feeds\CollectionFactory $collectionFactory;

    public function __construct(\Wyomind\DataFeedManager\Model\ResourceModel\Feeds\CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function getFeeds(): \Wyomind\DataFeedManager\Model\ResourceModel\Feeds\Collection
    {
        return $this->collectionFactory->create();
    }
}
