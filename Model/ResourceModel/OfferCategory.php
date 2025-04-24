<?php
declare(strict_types=1);

namespace DnD\Offers\Model\ResourceModel;

use DnD\Offers\Api\Data\OfferCategoryInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class OfferCategory extends AbstractDb implements OfferCategoryInterface
{
    protected function _construct()
    {
        $this->_init('dnd_offer_category', 'entity_id');
    }
}
