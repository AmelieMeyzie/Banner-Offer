<?php
declare(strict_types=1);

namespace DnD\Offers\Ui\Component\Listing\Column;

use Magento\Catalog\Helper\Image;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class ProductImagePreview extends Column
{
    public function __construct(
        ContextInterface                       $context,
        UiComponentFactory                     $uiComponentFactory,
        private readonly StoreManagerInterface $storeManager,
        private readonly Image                 $imageHelper,
        array                                  $components = [],
        array                                  $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[$fieldName]) && $item[$fieldName] !== '') {
                    $url = $this->storeManager->getStore()->getBaseUrl() .
                        $item[$fieldName];
                } else {
                    $url = $this->imageHelper->getDefaultPlaceholderUrl('small_image');
                }
                $item[$fieldName . '_src'] = $url;
                $item[$fieldName . '_orig_src'] = $url;
            }
        }

        return $dataSource;
    }
}
