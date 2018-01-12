<?php
namespace Smile\Matrix\Model\ResourceModel\Row;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Smile\Matrix\Model\Row','Smile\Matrix\Model\ResourceModel\Row');
    }
}
