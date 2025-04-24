<?php
declare(strict_types=1);

namespace DnD\Offers\Controller\Adminhtml\Offer;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Setup\Module\Di\Code\Reader\InvalidFileException;
use Magento\Store\Model\StoreManagerInterface;

class ImageUploader extends Action implements HttpPostActionInterface
{
    public const OFFER_DIRECTORY = 'offers';

    public function __construct(
        Context                           $context,
        protected FileSystem              $fileSystem,
        protected UploaderFactory         $uploaderFactory,
        protected StoreManagerInterface   $storeManager,
        private readonly RequestInterface $request,
        private readonly UrlInterface     $urlInterface,
        private readonly UploaderFactory  $fileUploaderFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $result = [];
        try {
            $mediaDirectory = $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
            if (!$mediaDirectory->isDirectory(self::OFFER_DIRECTORY)) {
                $mediaDirectory->create(self::OFFER_DIRECTORY);
            }

            $file = $this->request->getFiles('offer');
            if (!empty($file['image'])) {
                $imageFile = $file['image'];
            }

            if (!empty($imageFile)) {
                $target = $mediaDirectory->getAbsolutePath(self::OFFER_DIRECTORY);

                $uploader = $this->fileUploaderFactory->create(['fileId' => $imageFile]);
                $uploader->setAllowedExtensions(['jpg','jpeg','png']);
                $uploader->setAllowRenameFiles(true);
                $result = $uploader->save($target);
                if (!isset($result['file'], $result['name'])) {
                    throw new InvalidFileException('Image not uploaded');
                }

                $result['url'] = sprintf(
                    '%s%s/%s/%s',
                    $this->urlInterface->getBaseUrl(),
                    UrlInterface::URL_TYPE_MEDIA,
                    self::OFFER_DIRECTORY,
                    $result['file']
                );

                $result['name'] = $result['file'];
            }
        } catch (Exception $exception) {
            $result = ['error' => $exception->getMessage()];
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
