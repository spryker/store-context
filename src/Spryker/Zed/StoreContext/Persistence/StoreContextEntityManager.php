<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Persistence;

use Generated\Shared\Transfer\StoreContextTransfer;
use Orm\Zed\StoreContext\Persistence\SpyStoreContext;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\StoreContext\Persistence\StoreContextPersistenceFactory getFactory()
 */
class StoreContextEntityManager extends AbstractEntityManager implements StoreContextEntityManagerInterface
{
    public function createStoreContext(StoreContextTransfer $storeContextTransfer): StoreContextTransfer
    {
        $storeContextEntity = $this->getFactory()
            ->createStoreContextMapper()
            ->mapStoreContextTransferToStoreContextEntity($storeContextTransfer, new SpyStoreContext());

        $storeContextEntity->save();

        return $this->getFactory()->createStoreContextMapper()
            ->mapStoreContextEntityToStoreContextTransfer(
                $storeContextEntity,
                $storeContextTransfer,
            );
    }

    public function updateStoreContext(StoreContextTransfer $storeContextTransfer): StoreContextTransfer
    {
        $storeContextEntity = $this->getFactory()
            ->createStoreContextQuery()
            ->filterByFkStore($storeContextTransfer->getStoreOrFail()->getIdStoreOrFail())
            ->findOne();

        if (!$storeContextEntity) {
            return $storeContextTransfer;
        }

        $storeContextEntity = $this->getFactory()
            ->createStoreContextMapper()
            ->mapStoreContextTransferToStoreContextEntity($storeContextTransfer, $storeContextEntity);

        $storeContextEntity->save();

        return $this->getFactory()
            ->createStoreContextMapper()
            ->mapStoreContextEntityToStoreContextTransfer($storeContextEntity, $storeContextTransfer);
    }
}
