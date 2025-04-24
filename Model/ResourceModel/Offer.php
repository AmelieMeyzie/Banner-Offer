<?php
declare(strict_types=1);

namespace DnD\Offers\Model\ResourceModel;

use DnD\Offers\Api\Data\OfferInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Offer extends AbstractDb implements OfferInterface
{
    protected function _construct()
    {
        $this->_init('dnd_offer', 'entity_id');
    }
}
