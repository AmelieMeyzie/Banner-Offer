<?php
declare(strict_types=1);

namespace DnD\Offers\Block\Adminhtml\Offer\Edit;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton implements ButtonProviderInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly UrlInterface     $urlBuilder
    ) {
    }

    public function getButtonData(): array
    {
        $data = [];
        $offerId = $this->request->getParam('entity_id');

        if ($offerId) {
            $data = [
                'label'    => __('Delete'),
                'class'    => 'delete',
                'on_click' => "deleteConfirm('"
                    . __('Are you sure you want to delete this offer ?')
                    . "', '" . $this->getDeleteUrl($offerId) . "')",
                'sort_order' => 10,
            ];
        }

        return $data;
    }

    public function getDeleteUrl(string $offerId): string
    {
        return $this->urlBuilder->getUrl('*/*/delete', ['entity_id' => $offerId]);
    }
}
