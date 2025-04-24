<?php
declare(strict_types=1);

namespace DnD\Offers\Controller\Adminhtml\Offer;

use DnD\Offers\Model\OfferRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    public function __construct(
        Context                          $context,
        private readonly PageFactory     $resultPageFactory,
        private readonly OfferRepository $offerRepository
    ) {
        parent::__construct($context);
    }

    public function execute(): Page|ResultInterface|ResponseInterface
    {
        $offerId = $this->getRequest()?->getParam('entity_id');

        if ($offerId) {
            try {
                $this->offerRepository->getById((int) $offerId);
            } catch (NoSuchEntityException) {
                $this->messageManager->addErrorMessage(__('This offer no longer exists.'));

                return $this->_redirect('*/*/');
            }
        }

        return $this->resultPageFactory->create();
    }
}
