<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Product inventory data
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class MobWeb_PendingOrderProductStock_Block_Catalog_Product_Edit_Tab_Inventory extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Inventory
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mobweb_pendingorderproductstock/catalog/product/tab/inventory.phtml');
    }

    /**
     * Returns the qty of this product from pending/processing orders
     *
     * @return integer
     */
    public function getPendingStock()
    {
        // Load the product
        $product = $this->getProduct();

        // Get the quantity of products from orders that are either "pending"
        // or "processing"
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql = sprintf("
        SELECT SUM(order_items.qty_ordered) AS pending_stock
        FROM sales_flat_order_item AS order_items
        INNER JOIN sales_flat_order AS orders ON orders.entity_id = order_items.order_id 
        WHERE order_items.product_id=%d AND (orders.status = 'pending') ORDER BY orders.increment_id DESC;
        ", $product->getId());
        $pending_stock = ($pending_stock = $connection->fetchOne($sql)) ? (int) $pending_stock : '0';

        return $pending_stock;
    }
}
