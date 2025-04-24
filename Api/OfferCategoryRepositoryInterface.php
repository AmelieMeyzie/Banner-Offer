<?php
declare(strict_types=1);

namespace DnD\Offers\Api;

use DnD\Offers\Api\Data\OfferCategoryInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

interface OfferCategoryRepositoryInterface
{
    public function save(OfferCategoryInterface $offerCategory): OfferCategoryInterface;

    public function getById(int $entityId): OfferCategoryInterface;

    public function delete(OfferCategoryInterface $offerCategory): bool;

    public function deleteById(int $entityId): bool;

    public function getCollection(): AbstractCollection;

    public function getResourceModel(): AbstractDb;

    public function getNewOfferCategory(): OfferCategoryInterface;
}
