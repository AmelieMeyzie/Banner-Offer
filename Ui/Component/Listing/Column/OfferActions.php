<?php
declare(strict_types=1);

namespace DnD\Offers\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class OfferActions extends Column
{
    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        private readonly UrlInterface $urlBuilder,
        array              $components = [],
        array              $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$fieldName]['edit'] = [
                    'href'   => $this->getActionUrl('edit', $item['entity_id']),
                    'label'  => __('Edit'),
                    'hidden' => false,
                ];
                $item[$fieldName]['delete'] = [
                    'href'   => $this->getActionUrl('delete', $item['entity_id']),
                    'label'  => __('Delete'),
                    'hidden' => false,
                ];
            }
        }

        return $dataSource;
    }

    private function getActionUrl(string $action, string $entityId): string
    {
        return $this->urlBuilder->getUrl('offers/offer/' . $action, ['entity_id' => $entityId]);
    }
}
