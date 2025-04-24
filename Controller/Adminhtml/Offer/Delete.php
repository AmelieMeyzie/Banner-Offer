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

class Delete extends Action
{
    public function __construct(
        Context                          $context,
        private readonly OfferRepository $offerRepository
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface|ResponseInterface|Redirect
    {
        $offerId = (int) $this->getRequest()?->getParam('entity_id');

        try {
            $this->offerRepository->deleteById($offerId);
            $this->messageManager->addSuccessMessage(__('The offer has been deleted.'));
        } catch (Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/index', ['_current' => true]);
    }
}
