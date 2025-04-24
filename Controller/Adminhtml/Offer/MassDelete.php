<?php
declare(strict_types=1);

namespace DnD\Offers\Controller\Adminhtml\Offer;

use DnD\Offers\Model\OfferRepository;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;

class MassDelete extends Action
{
    public function __construct(
        Context                          $context,
        private readonly OfferRepository $offerRepository
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface|ResponseInterface|Redirect
    {
        $parameterData = $this->getRequest()->getParams();
        $selectedIds = [];
        if (array_key_exists('selected', $parameterData)) {
            $selectedIds = $parameterData['selected'];
        }
        if (array_key_exists('excluded', $parameterData)) {
            $allOffers = $this->offerRepository->getCollection();
            $allOffersIds = [];
            foreach ($allOffers as $offer) {
                $allOffersIds[] = $offer['entity_id'];
            }
            if ($parameterData['excluded'] === 'false') {
                $selectedIds = $allOffersIds;
            } else {
                $selectedIds = array_diff($allOffersIds, $parameterData['excluded']);
            }
        }

        $deleted = 0;
        foreach ($selectedIds as $offerId) {
            try {
                $this->offerRepository->deleteById((int) $offerId);
                $deleted++;
            } catch (Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            }
        }

        if ($deleted !== 0) {
            if (count($selectedIds) !== $deleted) {
                $this->messageManager->addErrorMessage(
                    __('Failed to delete %1 offer(s).', count($selectedIds) - $deleted)
                );
            } else {
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 offer(s) have been deleted.', $deleted)
                );
            }
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/index', ['_current' => true]);
    }
}
