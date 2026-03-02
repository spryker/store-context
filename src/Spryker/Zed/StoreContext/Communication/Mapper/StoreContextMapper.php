<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreContextCollectionRequestTransfer;
use Generated\Shared\Transfer\StoreContextCollectionResponseTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class StoreContextMapper implements StoreContextMapperInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function mapStoreTransfersToStoreCollectionTransfer(array $storeTransfers): StoreCollectionTransfer
    {
        $storeCollectionTransfer = new StoreCollectionTransfer();

        foreach ($storeTransfers as $storeTransfer) {
            $storeCollectionTransfer->addStore($storeTransfer);
        }

        return $storeCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreCollectionTransfer $storeCollectionTransfer
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function mapStoreCollectionTransferToStoreTransfers(StoreCollectionTransfer $storeCollectionTransfer): array
    {
        $storeTransfers = [];
        foreach ($storeCollectionTransfer->getStores() as $storeTransfer) {
            $storeTransfers[] = $storeTransfer;
        }

        return $storeTransfers;
    }

    public function mapStoreTranferToStoreContextCollectionRequestTransfer(StoreTransfer $storeTransfer): StoreContextCollectionRequestTransfer
    {
        $storeContextCollectionRequestTransfer = new StoreContextCollectionRequestTransfer();

        $storeContextCollectionRequestTransfer->addContext(
            (new StoreContextTransfer())->setStore($storeTransfer)->setApplicationContextCollection(
                $storeTransfer->getapplicationContextCollection(),
            ),
        );

        return $storeContextCollectionRequestTransfer;
    }

    public function mapStoreContextCollectionResponseTranferToStoreResponseTransfer(
        StoreContextCollectionResponseTransfer $storeContextCollectionResponseTransfer
    ): StoreResponseTransfer {
        $storeResponseTransfer = new StoreResponseTransfer();

        $storeResponseTransfer->setIsSuccessful(count($storeContextCollectionResponseTransfer->getErrors()) === 0);
        if (count($storeContextCollectionResponseTransfer->getContexts()) > 0) {
            $storeResponseTransfer->setStore($storeContextCollectionResponseTransfer->getContexts()[0]->getStore());
        }

        $storeResponseTransfer->setMessages($this->mapErrorTransfersToMessageTransfers($storeContextCollectionResponseTransfer->getErrors()));

        return $storeResponseTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\MessageTransfer>
     */
    protected function mapErrorTransfersToMessageTransfers(ArrayObject $errorTransfers): ArrayObject
    {
        $messageTransfers = [];

        /**
         * @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
         */
        foreach ($errorTransfers as $errorTransfer) {
            $messageTransfers[] = (new MessageTransfer())
                ->setParameters($errorTransfer->getParameters())
                ->setValue($errorTransfer->getMessage());
        }

        return new ArrayObject($messageTransfers);
    }
}
