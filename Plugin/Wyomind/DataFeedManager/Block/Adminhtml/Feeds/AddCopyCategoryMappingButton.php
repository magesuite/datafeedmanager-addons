<?php
declare(strict_types=1);

namespace MageSuite\DataFeedManagerAddons\Plugin\Wyomind\DataFeedManager\Block\Adminhtml\Feeds;

class AddCopyCategoryMappingButton
{
    protected \Magento\Framework\Escaper $escaper;

    public function __construct(\Magento\Framework\Escaper $escaper)
    {
        $this->escaper = $escaper;
    }

    public function beforeSetLayout(\Wyomind\DataFeedManager\Block\Adminhtml\Feeds $subject): void
    {
        $url = $subject->getUrl('datafeedmanageraddons/category/index');
        $subject->addButton(
            'copy_categories',
            [
                'label' => __('Copy Categories'),
                'class' => 'copy categories',
                'id' => 'copy-categories',
                'onclick' => sprintf("setLocation('%s')", $url)
            ]
        );
    }
}
