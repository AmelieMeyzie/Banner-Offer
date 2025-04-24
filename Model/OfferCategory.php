<?php
declare(strict_types=1);

namespace DnD\Offers\Model;

use DnD\Offers\Api\Data\OfferCategoryInterface;
use Magento\Framework\Model\AbstractModel;

class OfferCategory extends AbstractModel implements OfferCategoryInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\OfferCategory::class);
    }
}
