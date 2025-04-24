<?php
declare(strict_types=1);

namespace DnD\Offers\Model\ResourceModel\Offer;

use DnD\Offers\Model\Offer;
use DnD\Offers\Model\ResourceModel\Offer as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Offer::class, ResourceModel::class);
    }
}
