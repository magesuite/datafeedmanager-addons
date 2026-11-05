<?php

declare(strict_types=1);

namespace MageSuite\DataFeedManagerAddons\Plugin\Wyomind\DataFeedManager\Helper\AttributesImages;

class UseResizedImageUrl
{
    protected const PRODUCT_IMAGE_DIRECTORY = 'catalog/product/';

    public function __construct(
        protected \MageSuite\DataFeedManagerAddons\Model\Product\ResizedImageUrl $resizedImageUrl
    ) {}

    public function afterImageLink(\Wyomind\DataFeedManager\Helper\AttributesImages $subject, string $result, \Wyomind\DataFeedManager\Model\Feeds $model): string
    {
        $productImagesUrl = sprintf('%s%s', $model->baseImg, self::PRODUCT_IMAGE_DIRECTORY);

        if (!str_starts_with($result, $productImagesUrl)) {
            return $result;
        }

        $imageFile = sprintf('/%s', substr($result, strlen($productImagesUrl)));

        return $this->resizedImageUrl->getUrl($imageFile, (int)$model->params['store_id'], $model->baseImg);
    }
}
