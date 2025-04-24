<?php
declare(strict_types=1);

namespace DnD\Offers\ViewModel\Category;

use DateTime;
use DnD\Offers\Helper\OfferHelper;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Banner implements ArgumentInterface
{
    public function __construct(
        private readonly RequestInterface  $request,
        private readonly OfferHelper $offerHelper
    ) {
    }

    public function getOffers(): ?array
    {
        $offers = [];
        $categoryId = $this->request->getParam('id');

        if ($categoryId) {
            $offerIds = $this->offerHelper->getOfferIdsByCategoryId((int) $categoryId);

            if ($offerIds) {
                $offersList = $this->offerHelper->getOffersByIds($offerIds);
                $now = new DateTime();
                foreach ($offersList as $offer) {
                    // Check dates to display only active offers
                    if ($offer->getFromDate()) {
                        $fromDate = new DateTime($offer->getFromDate());
                        if ($fromDate > $now) {
                            continue;
                        }
                    }

                    if ($offer->getToDate()) {
                        $toDate = new DateTime($offer->getToDate());
                        if ($toDate < $now) {
                            continue;
                        }
                    }

                    $offers[] = $offer;
                }
            }
        }

        return $offers;
    }
}
