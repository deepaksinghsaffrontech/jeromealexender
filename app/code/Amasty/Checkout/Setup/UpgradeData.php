<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    /**
     * @var Operation\UpgradeDataTo203
     */
    private $upgradeDataTo203;

    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    public function __construct(
        Operation\UpgradeDataTo203\Proxy $upgradeDataTo203,
        \Magento\Framework\App\State $appState
    ) {
        $this->upgradeDataTo203 = $upgradeDataTo203;
        $this->appState = $appState;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->appState->emulateAreaCode(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
            [$this, 'upgradeDataWithEmulationAreaCode'],
            [$setup, $context]
        );
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgradeDataWithEmulationAreaCode(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '2.0.3', '<')) {
            $this->upgradeDataTo203->execute();
        }

        $setup->endSetup();
    }
}
