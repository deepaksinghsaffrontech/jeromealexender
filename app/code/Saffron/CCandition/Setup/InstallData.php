<?php

namespace Saffron\CCandition\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
 
/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
 
    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
 
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        if (version_compare($context->getVersion(), '1.0.0') < 0){





		$eavSetup -> removeAttribute(\Magento\Catalog\Model\Category::ENTITY, 'homeimage');

		
			$eavSetup -> addAttribute(\Magento\Catalog\Model\Category :: ENTITY, 'homeimage', [
                        'type' => 'varchar',
                        'label' => 'Home Image',
                        'input' => 'image',
                        'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
						'required' => false,
                        'sort_order' => 110,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
						"default" => "",
						"class"    => "",
						"note"       => ""
			]
			);
					
	
	

		$eavSetup -> removeAttribute(\Magento\Catalog\Model\Category::ENTITY, 'showhome');

		
			$eavSetup -> addAttribute(\Magento\Catalog\Model\Category :: ENTITY, 'showhome', [
                        'type' => 'int',
                        'label' => 'Show on Home',
                        'input' => 'select',
						'required' => false,
                        'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                        'sort_order' => 120,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
						"default" => "",
						"class"    => "",
						"note"       => ""
			]
			);
					
	
	



		}

    }
}