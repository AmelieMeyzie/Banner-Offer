<?php
declare(strict_types=1);

namespace DnD\Offers\Model;

use DnD\Offers\Api\Data\OfferInterface;
use DnD\Offers\Api\OfferRepositoryInterface;
use DnD\Offers\Model\ResourceModel\Offer\Collection;
use DnD\Offers\Model\ResourceModel\Offer as ResourceModel;
use DnD\Offers\Model\ResourceModel\Offer\CollectionFactory;
use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class OfferRepository implements OfferRepositoryInterface
{
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly ResourceModel     $resourceModel,
        private readonly OfferFactory      $offerFactory
    ) {
    }

    public function save(OfferInterface $offer): OfferInterface
    {
        try {
            $this->getResourceModel()->save($offer);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $offer;
    }

    public function getById(int $entityId): OfferInterface
    {
        $offer = $this->getNewOffer();
        $this->getResourceModel()->load($offer, $entityId);
        if (!$offer->getId()) {
            throw new NoSuchEntityException(__('Offer with id "%1" does not exist.', $entityId));
        }

        return $offer;
    }

    public function delete(OfferInterface $offer): bool
    {
        try {
            $this->getResourceModel()->delete($offer);
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

    public function getNewOffer(): Offer
    {
        return $this->offerFactory->create();
    }
}
