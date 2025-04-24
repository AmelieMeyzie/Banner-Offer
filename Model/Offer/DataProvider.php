<?php
declare(strict_types=1);

namespace DnD\Offers\Model\Offer;

use DnD\Offers\Helper\OfferHelper;
use DnD\Offers\Model\ResourceModel\Offer\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory                      $collectionFactory,
        private readonly StoreManagerInterface $storeManager,
        private readonly OfferHelper           $offerHelper,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    public function getData(): array
    {
        $offers = $this->collection->getItems();
        $storeUrl = $this->storeManager->getStore()->getBaseUrl();

        foreach ($offers as $offer) {
            $offerData = $offer->getData();
            $offerId = (int) $offer->getEntityId();

            // Get image
            $offerImage = $offerData['image'];
            unset($offerData['image']);
            if ($offerImage) {
                $offerData['image'][0]['name'] = $this->getImageName($offerImage);
                $offerData['image'][0]['url'] = $storeUrl . $offerImage;
            }

            // Get category
            $offerData['category'] = $this->offerHelper->getCategoryList($offerId);

            $this->data[$offerId]['offer'] = $offerData;
        }

        return $this->data;
    }

    private function getImageName(string $imagePath): string
    {
        $path = explode('/', $imagePath);

        return array_pop($path);
    }
}
