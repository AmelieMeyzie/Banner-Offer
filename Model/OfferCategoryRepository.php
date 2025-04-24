<?php
declare(strict_types=1);

namespace DnD\Offers\Model;

use DnD\Offers\Api\Data\OfferCategoryInterface;
use DnD\Offers\Api\OfferCategoryRepositoryInterface;
use DnD\Offers\Model\ResourceModel\OfferCategory\Collection;
use DnD\Offers\Model\ResourceModel\OfferCategory as ResourceModel;
use DnD\Offers\Model\ResourceModel\OfferCategory\CollectionFactory;
use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class OfferCategoryRepository implements OfferCategoryRepositoryInterface
{
    public function __construct(
        private readonly CollectionFactory    $collectionFactory,
        private readonly ResourceModel        $resourceModel,
        private readonly OfferCategoryFactory $offerCategoryFactory
    ) {
    }

    public function save(OfferCategoryInterface $offerCategory): OfferCategoryInterface
    {
        try {
            $this->getResourceModel()->save($offerCategory);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $offerCategory;
    }

    public function getById(int $entityId): OfferCategoryInterface
    {
        $offerCategory = $this->getNewOfferCategory();
        $this->getResourceModel()->load($offerCategory, $entityId);
        if (!$offerCategory->getId()) {
            throw new NoSuchEntityException(__('Offer Category with id "%1" does not exist.', $entityId));
        }

        return $offerCategory;
    }

    public function delete(OfferCategoryInterface $offerCategory): bool
    {
        try {
            $this->getResourceModel()->delete($offerCategory);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    public function deleteById(int $entityId): bool
    {
        return $this->delete($this->getById($entityId));
    }

    public function getCollection(): Collection
    {
        return $this->collectionFactory->create();
    }

    public function getResourceModel(): ResourceModel
    {
        return $this->resourceModel;
    }

    public function getNewOfferCategory(): OfferCategory
    {
        return $this->offerCategoryFactory->create();
    }
}
