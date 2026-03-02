<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\StoreContext\Business\Creator\StoreContextCreator;
use Spryker\Zed\StoreContext\Business\Creator\StoreContextCreatorInterface;
use Spryker\Zed\StoreContext\Business\Expander\StoreExpander;
use Spryker\Zed\StoreContext\Business\Expander\StoreExpanderInterface;
use Spryker\Zed\StoreContext\Business\Reader\StoreContextReader;
use Spryker\Zed\StoreContext\Business\Reader\StoreContextReaderInterface;
use Spryker\Zed\StoreContext\Business\Reader\TimezoneReader;
use Spryker\Zed\StoreContext\Business\Reader\TimezoneReaderInterface;
use Spryker\Zed\StoreContext\Business\Updater\StoreContextUpdater;
use Spryker\Zed\StoreContext\Business\Updater\StoreContextUpdaterInterface;
use Spryker\Zed\StoreContext\Business\Validator\Rule\ApplicationRule;
use Spryker\Zed\StoreContext\Business\Validator\Rule\ContextAlreadyExistRule;
use Spryker\Zed\StoreContext\Business\Validator\Rule\ContextNotFoundRule;
use Spryker\Zed\StoreContext\Business\Validator\Rule\DefaultContextExistRule;
use Spryker\Zed\StoreContext\Business\Validator\Rule\OneContextPerApplicationRule;
use Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface;
use Spryker\Zed\StoreContext\Business\Validator\Rule\TimezoneRule;
use Spryker\Zed\StoreContext\Business\Validator\StoreContextValidator;
use Spryker\Zed\StoreContext\Business\Validator\StoreContextValidatorInterface;
use Spryker\Zed\StoreContext\Business\Writer\StoreContextWriter;
use Spryker\Zed\StoreContext\Business\Writer\StoreContextWriterInterface;

/**
 * @method \Spryker\Zed\StoreContext\StoreContextConfig getConfig()
 * @method \Spryker\Zed\StoreContext\Persistence\StoreContextRepositoryInterface getRepository()
 * @method \Spryker\Zed\StoreContext\Persistence\StoreContextEntityManagerInterface getEntityManager()
 */
class StoreContextBusinessFactory extends AbstractBusinessFactory
{
    public function createStoreExpander(): StoreExpanderInterface
    {
        return new StoreExpander(
            $this->createStoreContextReader(),
            $this->getConfig(),
        );
    }

    public function createStoreContextWriter(): StoreContextWriterInterface
    {
        return new StoreContextWriter($this->getEntityManager());
    }

    public function createStoreContextReader(): StoreContextReaderInterface
    {
        return new StoreContextReader($this->getRepository());
    }

    public function createStoreContextCreator(): StoreContextCreatorInterface
    {
        return new StoreContextCreator(
            $this->createStoreContextWriter(),
            $this->createStoreContextCreateValidator(),
        );
    }

    public function createStoreContextUpdater(): StoreContextUpdaterInterface
    {
        return new StoreContextUpdater(
            $this->createStoreContextWriter(),
            $this->createStoreContextUpdateValidator(),
        );
    }

    public function createStoreContextCreateValidator(): StoreContextValidatorInterface
    {
        return new StoreContextValidator($this->getCreateValidatorRules());
    }

    public function createStoreContextUpdateValidator(): StoreContextValidatorInterface
    {
        return new StoreContextValidator($this->getUpdateValidatorRules());
    }

    public function createStoreContextValidator(): StoreContextValidatorInterface
    {
        return new StoreContextValidator($this->getDefaultValidatorRules());
    }

    public function createTimezoneReader(): TimezoneReaderInterface
    {
        return new TimezoneReader();
    }

    /**
     * @return array<\Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface>
     */
    public function getDefaultValidatorRules(): array
    {
        return [
            $this->createApplicationRule(),
            $this->createDefaultConfigurationRule(),
            $this->createOneContextPerApplicationRule(),
            $this->createTimezoneRule(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface>
     */
    public function getCreateValidatorRules(): array
    {
        return [
            $this->createContextAlreadyExistRule(),
            $this->createApplicationRule(),
            $this->createDefaultConfigurationRule(),
            $this->createOneContextPerApplicationRule(),
            $this->createTimezoneRule(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface>
     */
    public function getUpdateValidatorRules(): array
    {
        return [
            $this->createContextNotFoundRule(),
            $this->createApplicationRule(),
            $this->createDefaultConfigurationRule(),
            $this->createOneContextPerApplicationRule(),
            $this->createTimezoneRule(),
        ];
    }

    public function createTimezoneRule(): StoreContextValidatorRuleInterface
    {
        return new TimezoneRule($this->createTimezoneReader());
    }

    public function createOneContextPerApplicationRule(): StoreContextValidatorRuleInterface
    {
        return new OneContextPerApplicationRule($this->getConfig());
    }

    public function createDefaultConfigurationRule(): StoreContextValidatorRuleInterface
    {
        return new DefaultContextExistRule();
    }

    public function createApplicationRule(): StoreContextValidatorRuleInterface
    {
        return new ApplicationRule($this->getConfig());
    }

    public function createContextAlreadyExistRule(): StoreContextValidatorRuleInterface
    {
        return new ContextAlreadyExistRule(
            $this->createStoreContextReader(),
        );
    }

    public function createContextNotFoundRule(): StoreContextValidatorRuleInterface
    {
        return new ContextNotFoundRule(
            $this->createStoreContextReader(),
        );
    }
}
