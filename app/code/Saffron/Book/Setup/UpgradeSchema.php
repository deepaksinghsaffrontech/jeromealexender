<?php

namespace Saffron\Book\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $quoteAddressTable = 'quote_address';
        $quoteTable = 'quote';
        $orderTable = 'sales_order';
        $invoiceTable = 'sales_invoice';
        $creditmemoTable = 'sales_creditmemo';

		
        //Quote address tables
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($quoteAddressTable),
                'bookdiscount',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length'=>'12,4',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Bookdiscount'
                ]
            );
        $setup->getConnection()
            ->addColumn(
              $setup->getTable($quoteAddressTable),
                'base_bookdiscount',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length'=>'12,4',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Base Bookdiscount'
                ]
            );
        //Quote tables
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($quoteTable),
                'bookdiscount',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length'=>'12,4',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Bookdiscount'

                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($quoteTable),
                'base_bookdiscount',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length'=>'12,4',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Base Bookdiscount'

                ]
            );
        //Order tables
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'bookdiscount',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length'=>'12,4',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Bookdiscount'

                ]
            );

         $setup->getConnection()
             ->addColumn(
                $setup->getTable($orderTable),
                'base_bookdiscount',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length'=>'12,4',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Base Bookdiscount'

                ]
            );
        //Invoice tables
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($invoiceTable),
                'bookdiscount',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length'=>'12,4',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Bookdiscount'

                ]
            );
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($invoiceTable),
                'base_bookdiscount',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length'=>'12,4',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Base Bookdiscount'

                ]
            );
        //Credit memo tables
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($creditmemoTable),
                'bookdiscount',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length'=>'12,4',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Bookdiscount'

                ]
            );
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($creditmemoTable),
                'base_bookdiscount',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length'=>'12,4',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Base Bookdiscount'

                ]
            );


        $setup->endSetup();
    }
}
