<?php
namespace Smile\Matrix\Model;
class Row extends \Magento\Framework\Model\AbstractModel implements \Smile\Matrix\Api\Data\RowInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'smile_matrix_row';

    protected function _construct()
    {
        $this->_init('Smile\Matrix\Model\ResourceModel\Row');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
