<?php
declare(strict_types=1);

namespace DnD\Offers\Helper;

use DnD\Offers\Model\ResourceModel\Offer\CollectionFactory;
use DnD\Offers\Model\ResourceModel\OfferCategory\CollectionFactory as OfferCategoryCollectionFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class OfferHelper extends AbstractHelper
{
    public function __construct(
        Context                                        $context,
        private readonly StoreManagerInterface         $storeManager,
        private readonly CollectionFactory             $offerCollectionFactory,
        private readonly OfferCategoryCollectionFactory $offerCategoryCollectionFactory
    ) {
        parent::__construct($context);
    }

    public function getImageName(array $imageData): string
    {
        $image = array_shift($imageData);
        if (is_array($image) && str_contains($image['url'], $this->storeManager->getStore()->getBaseUrl())) {
            return str_replace($this->storeManager->getStore()->getBaseUrl(), '', $image['url']);
        }

        return substr($image['url'], 1);
    }

    public function getOfferCategoryIdsByOfferId(int $offerId): array
    {
        return $this->offerCategoryCollectionFactory->create()
            ->addFieldToFilter('offer_id', ['eq' => $offerId])
            ->getAllIds();
    }

    public function getCategoryIdsByOfferId(int $offerId): array
    {
        return $this->offerCategoryCollectionFactory->create()
            ->addFieldToFilter('offer_id', ['eq' => $offerId])
            ->getColumnValues('category_id');
    }

    public function getCategoryList(int $offerId): array
    {
        return $this->getCategoryIdsByOfferId($offerId);
    }

    public function getOfferIdsByCategoryId(int $categoryId): array
    {
        return $this->offerCategoryCollectionFactory->create()
            ->addFieldToFilter('category_id', ['eq' => $categoryId])
            ->getColumnValues('offer_id');
    }

    public function getOffersByIds(array $offerIds): array
    {
        return $this->offerCollectionFactory->create()
            ->addFieldToFilter('entity_id', ['in' => $offerIds])
            ->getItems();
    }
}
