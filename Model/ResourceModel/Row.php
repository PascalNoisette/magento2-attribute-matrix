<?php
namespace Smile\Matrix\Model\ResourceModel;
class Row extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('smile_matrix_row','smile_matrix_row_id');
    }
    
    /**
     * Delete Tier Prices for product
     *
     * @param int $productId
     * @param int $websiteId
     * @param int $priceId
     * @return int The number of affected rows
     */
    public function deleteMatrixData($productId)
    {
        $connection = $this->getConnection();

        $conds = [$connection->quoteInto($this->getProductIdFieldName() . ' = ?', $productId)];

        $where = implode(' AND ', $conds);

        return $connection->delete($this->getMainTable(), $where);
    }
    
    
    /**
     * Load Tier Prices for product
     *
     * @param int $productId
     * @param int $websiteId
     * @return array
     */
    public function loadMatrixData($productId)
    {
         $columns = [
            'data_name',
            'data_value',
        ];

        $select = $this->getConnection()->select()
            ->from($this->getMainTable(), $columns);

        $productIdFieldName = $this->getProductIdFieldName();
        $select->where("{$productIdFieldName} = ?", $productId);

        return $this->getConnection()->fetchAll($select);
    }
    
    
    /**
     * Save tier price object
     *
     * @param \Magento\Framework\DataObject $priceObject
     * @return \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Tierprice
     */
    public function savePriceData(\Magento\Framework\DataObject $priceObject)
    {
        $connection = $this->getConnection();
        $data = $this->_prepareDataForTable($priceObject, $this->getMainTable());

        if (!empty($data[$this->getIdFieldName()])) {
            $where = $connection->quoteInto($this->getIdFieldName() . ' = ?', $data[$this->getIdFieldName()]);
            unset($data[$this->getIdFieldName()]);
            $connection->update($this->getMainTable(), $data, $where);
        } else {
            $connection->insert($this->getMainTable(), $data);
        }
        return $this;
    }
    
    /**
     * Save tier price object
     *
     * @param \Magento\Framework\DataObject $priceObject
     * @return \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Tierprice
     */
    public function savePriceData(\Magento\Framework\DataObject $priceObject)
    {
        $connection = $this->getConnection();
        $data = $this->_prepareDataForTable($priceObject, $this->getMainTable());

        if (!empty($data[$this->getIdFieldName()])) {
            $where = $connection->quoteInto($this->getIdFieldName() . ' = ?', $data[$this->getIdFieldName()]);
            unset($data[$this->getIdFieldName()]);
            $connection->update($this->getMainTable(), $data, $where);
        } else {
            $connection->insertArray($this->getMainTable(), , $data);
        }
        return $this;
    }
    
    
    /**
     * @return string
     */
    protected function getProductIdFieldName()
    {
        $table = $this->getTable('catalog_product_entity');
        $indexList = $this->getConnection()->getIndexList($table);
        return $indexList[$this->getConnection()->getPrimaryKeyName($table)]['COLUMNS_LIST'][0];
    }
}
