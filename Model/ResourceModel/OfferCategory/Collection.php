<?php
declare(strict_types=1);

namespace DnD\Offers\Model\ResourceModel\OfferCategory;

use DnD\Offers\Model\OfferCategory;
use DnD\Offers\Model\ResourceModel\OfferCategory as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(OfferCategory::class, ResourceModel::class);
    }
}
