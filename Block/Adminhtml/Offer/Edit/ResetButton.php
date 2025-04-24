<?php
declare(strict_types=1);

namespace DnD\Offers\Block\Adminhtml\Offer\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ResetButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        return [
            'label'      => __('Reset'),
            'class'      => 'reset',
            'on_click'   => 'location.reload();',
            'sort_order' => 20
        ];
    }
}
