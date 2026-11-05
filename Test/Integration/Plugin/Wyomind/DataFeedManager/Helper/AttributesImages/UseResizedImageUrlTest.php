<?php

declare(strict_types=1);

namespace MageSuite\DataFeedManagerAddons\Test\Integration\Plugin\Wyomind\DataFeedManager\Helper\AttributesImages;

#[\Magento\TestFramework\Fixture\AppArea('adminhtml')]
#[\Magento\TestFramework\Fixture\DbIsolation(true)]
#[\Magento\TestFramework\Fixture\AppIsolation(true)]
class UseResizedImageUrlTest extends \PHPUnit\Framework\TestCase
{
    protected const IMAGE_FILE = '/m/a/magento_image.jpg';
    protected const FEED_MEDIA_URL = 'https://feed.example.com/media/';

    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\Magento\Catalog\Api\ProductRepositoryInterface $productRepository;
    protected ?\Wyomind\DataFeedManager\Helper\AttributesImages $attributesImages;

    protected function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->productRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->attributesImages = $this->objectManager->get(\Wyomind\DataFeedManager\Helper\AttributesImages::class);
    }

    #[\Magento\TestFramework\Fixture\DataFixture('Magento/Catalog/_files/product_with_image.php')]
    public function testImageLinkReturnsProductPageImageUrlOnFeedMediaUrl(): void
    {
        $product = $this->productRepository->get('simple');

        $imageLink = $this->attributesImages->imageLink($this->createFeed(), ['index' => 0], $product, 'product');

        $this->assertSame($this->getExpectedImageUrl(), $imageLink);
    }

    #[\Magento\TestFramework\Fixture\Config('web/unsecure/base_media_url', 'http://store.example.com/media/', 'store', 'default')]
    #[\Magento\TestFramework\Fixture\DataFixture('Magento/Catalog/_files/product_with_image.php')]
    public function testImageLinkKeepsFeedMediaUrlWhenCurrentStoreDiffers(): void
    {
        $product = $this->productRepository->get('simple');
        $storeManager = $this->objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
        $storeManager->setCurrentStore(\Magento\Store\Model\Store::ADMIN_CODE);

        $imageLink = $this->attributesImages->imageLink($this->createFeed(), ['index' => 0], $product, 'product');

        $this->assertSame($this->getExpectedImageUrl(), $imageLink);
        $this->assertSame(\Magento\Store\Model\Store::ADMIN_CODE, $storeManager->getStore()->getCode());
    }

    public function testPlaceholderImageLinkIsKept(): void
    {
        $product = $this->objectManager->create(\Magento\Catalog\Model\Product::class);
        $product->setImage('no_selection');
        $feed = $this->createFeed();
        $feed->defaultImage = 'default/placeholder.jpg';

        $imageLink = $this->attributesImages->imageLink($feed, ['index' => 0], $product, 'product');

        $this->assertSame(sprintf('%s/catalog/product/placeholder/default/placeholder.jpg', self::FEED_MEDIA_URL), $imageLink);
    }

    protected function createFeed(): \Wyomind\DataFeedManager\Model\Feeds
    {
        $feed = $this->objectManager->create(\Wyomind\DataFeedManager\Model\Feeds::class);
        $feed->baseImg = self::FEED_MEDIA_URL;
        $feed->params = ['store_id' => 1];

        return $feed;
    }

    protected function getExpectedImageUrl(): string
    {
        $emulation = $this->objectManager->get(\Magento\Store\Model\App\Emulation::class);
        $emulation->startEnvironmentEmulation(1, \Magento\Framework\App\Area::AREA_FRONTEND, true);
        $productPageImageUrl = $this->objectManager->create(\Magento\Catalog\Model\Product\Image\UrlBuilder::class)->getUrl(self::IMAGE_FILE, 'product_page_image_large');
        $storeMediaUrl = $this->objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getStore(1)->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $emulation->stopEnvironmentEmulation();

        $this->assertStringStartsWith(sprintf('%scatalog/product/thumbnail/', $storeMediaUrl), $productPageImageUrl);

        return str_replace($storeMediaUrl, self::FEED_MEDIA_URL, $productPageImageUrl);
    }
}
