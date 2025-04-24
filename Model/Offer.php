<?php
declare(strict_types=1);

namespace DnD\Offers\Model;

use DnD\Offers\Api\Data\OfferInterface;
use Magento\Framework\Model\AbstractModel;

class Offer  extends AbstractModel implements OfferInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Offer::class);
    }
}
