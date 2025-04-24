<?php
declare(strict_types=1);

namespace DnD\Offers\Controller\Adminhtml\Offer;

use Magento\Backend\App\Action;

class NewAction extends Action
{
    public function execute(): void
    {
        $this->_forward('edit');
    }
}
