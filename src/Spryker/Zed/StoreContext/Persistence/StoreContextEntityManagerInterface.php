<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Persistence;

use Generated\Shared\Transfer\StoreContextTransfer;

interface StoreContextEntityManagerInterface
{
    public function createStoreContext(StoreContextTransfer $storeContextTransfer): StoreContextTransfer;

    public function updateStoreContext(StoreContextTransfer $storeContextTransfer): StoreContextTransfer;
}
