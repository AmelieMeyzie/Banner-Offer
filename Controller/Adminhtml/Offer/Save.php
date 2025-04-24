<?php
declare(strict_types=1);

namespace DnD\Offers\Controller\Adminhtml\Offer;

use DateTime;
use DnD\Offers\Api\Data\OfferInterface;
use DnD\Offers\Api\OfferCategoryRepositoryInterface;
use DnD\Offers\Helper\OfferHelper;
use DnD\Offers\Model\OfferCategoryFactory;
use DnD\Offers\Model\OfferFactory;
use DnD\Offers\Model\OfferRepository;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    public function __construct(
        Context                                           $context,
        private readonly OfferFactory                     $offerFactory,
        private readonly OfferRepository                  $offerRepository,
        private readonly OfferHelper                      $offerHelper,
        private readonly OfferCategoryRepositoryInterface $offerCategoryRepository,
        private readonly OfferCategoryFactory             $offerCategoryFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface|ResponseInterface|Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $offerData = $this->getRequest()?->getParam('offer');

        if (is_array($offerData)) {
            // If there is an id, edit the offer with this id
            // Otherwise, create a new offer
            if (!empty($offerData['entity_id'])) {
                try {
                    $offer = $this->offerRepository->getById((int) $offerData['entity_id']);
                } catch (NoSuchEntityException $exception) {
                    $this->messageManager->addErrorMessage($exception->getMessage());
                }
            } else {
                $offer = $this->offerFactory->create();
            }

            // Check that the date range is possible
            if (isset($offerData['from_date'], $offerData['to_date'])) {
                $fromDate = new DateTime($offerData['from_date']);
                $toDate = new DateTime($offerData['to_date']);

                if ($fromDate > $toDate) {
                    $this->messageManager->addErrorMessage(__('To Date must follow From Date.'));

                    return $resultRedirect->setPath('*/*/index');
                }
            }

            /** @var OfferInterface $offer */
            $offer->setLabel($offerData['label'])
                ->setDescription($offerData['description'])
                ->setImage(isset($offerData['image']) ?
                    $this->offerHelper->getImageName($offerData['image']) : '')
                ->setUrl($offerData['url'] ?? '')
                ->setFromDate($offerData['from_date'] ?? '')
                ->setToDate($offerData['to_date'] ?? '');

            try {
                $offer = $this->offerRepository->save($offer);
            } catch (Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());

                return $resultRedirect->setPath('*/*/index');
            }

            $this->manageCategories($offerData, $offer);

            $this->messageManager->addSuccessMessage(__('The offer has been saved.'));

            return $resultRedirect->setPath('*/*/index');
        }
        $this->messageManager->addErrorMessage(__('Something went wrong while saving this offer.'));

        return $resultRedirect->setPath('*/*/*');
    }

    public function manageCategories(array $offerData, OfferInterface $offer): void
    {
        if (isset($offerData['category']) && is_array($offerData['category'])) {
            $offerCategoryIds = $this->offerHelper->getOfferCategoryIdsByOfferId((int) $offer->getEntityId());
            if (!empty($offerCategoryIds)) {
                foreach ($offerCategoryIds as $offerCategoryId) {
                    $this->offerCategoryRepository->deleteById((int)$offerCategoryId);
                }
            }

            foreach ($offerData['category'] as $categoryId) {
                $offerCategory = $this->offerCategoryFactory->create()
                    ->setOfferId($offer->getEntityId())
                    ->setCategoryId($categoryId);

                $this->offerCategoryRepository->save($offerCategory);
            }
        }
    }
}
