<?php

declare(strict_types=1);

namespace MageSuite\DataFeedManagerAddons\Model\Product;

class ResizedImageUrl
{
    protected array $imageParams = [];

    public function __construct(
        protected \Magento\Framework\View\ConfigInterface $presentationConfig,
        protected \Magento\Framework\View\DesignInterface $design,
        protected \Magento\Catalog\Model\Product\Image\ParamsBuilder $imageParamsBuilder,
        protected \Magento\Catalog\Model\View\Asset\ImageFactory $viewAssetImageFactory,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected string $imageId = 'product_page_image_large'
    ) {}

    public function getUrl(string $file, int $storeId, string $mediaBaseUrl): string
    {
        $resizedImageUrl = $this->viewAssetImageFactory->create(['miscParams' => $this->getImageParams($storeId), 'filePath' => $file])->getUrl();
        $storeMediaBaseUrl = $this->storeManager->getStore($storeId)->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        if (!str_starts_with($resizedImageUrl, $storeMediaBaseUrl)) {
            return $resizedImageUrl;
        }

        return sprintf('%s%s', $mediaBaseUrl, substr($resizedImageUrl, strlen($storeMediaBaseUrl)));
    }

    protected function getImageParams(int $storeId): array
    {
        if (isset($this->imageParams[$storeId])) {
            return $this->imageParams[$storeId];
        }

        $themeId = $this->design->getConfigurationDesignTheme(\Magento\Framework\App\Area::AREA_FRONTEND, ['store' => $storeId]);
        $viewConfig = $this->presentationConfig->getViewConfig(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'themeId' => $themeId]);
        $imageArguments = $viewConfig->getMediaAttributes('Magento_Catalog', \Magento\Catalog\Helper\Image::MEDIA_TYPE_CONFIG_NODE, $this->imageId);
        $this->imageParams[$storeId] = $this->imageParamsBuilder->build($imageArguments, $storeId);

        return $this->imageParams[$storeId];
    }
}
