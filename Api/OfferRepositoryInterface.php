<?php
declare(strict_types=1);

namespace DnD\Offers\Api;

use DnD\Offers\Api\Data\OfferInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

interface OfferRepositoryInterface
{
    public function save(OfferInterface $offer): OfferInterface;

    public function getById(int $entityId): OfferInterface;

    public function delete(OfferInterface $offer): bool;

    public function deleteById(int $entityId): bool;

    public function getCollection(): AbstractCollection;

    public function getResourceModel(): AbstractDb;

    public function getNewOffer(): OfferInterface;
}
