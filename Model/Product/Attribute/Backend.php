<?php

namespace Smile\Matrix\Model\Product\Attribute;


class Backend extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * Backend table ressource
     *
     * @var \Smile\Matrix\Model\ResourceModel\Row
     */
    protected $_backendTable;

    /**
     * @param \Smile\Matrix\Model\ResourceModel\Row $backend
     */
    public function __construct(
        \Smile\Matrix\Model\ResourceModel\Row $backend
    ) {
        $this->_backendTable = $backend;
    }

    /**
     * Retrieve resource instance
     *
     * @return \Smile\Matrix\Model\ResourceModel\Row
     */
    protected function _getResource()
    {
        return $this->_backendTable;
    }
    
    /**
     * Assign group prices to product data
     *
     * @param \Magento\Catalog\Model\Product $object
     * @return $this
     */
    public function afterLoad($object)
    {
        $productId = $object->getData($this->getMetadataPool()->getMetadata(ProductInterface::class)->getLinkField());
        $attrCode = $this->getAttribute()->getAttributeCode();
        $data = $this->_getResource()->loadMatrixData($productId);
        $object->setData($attrCode, $data);

        return $this;
    }

    
    
    /**
     * After Save Attribute manipulation
     *
     * @param \Magento\Catalog\Model\Product $object
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function afterSave($object)
    {
        $priceRows = $object->getData($this->getAttribute()->getName());
        if (null === $priceRows) {
            return $this;
        }

        $new = [];
        // prepare data for save
        foreach ($priceRows as $data) {
            if (!empty($data['delete'])) {
                continue;
            }
            if (empty($data['data_name'])) {
                continue;
            }
            if (empty($data['data_value'])) {
                continue;
            }

            $new[$data['data_name']] = $data['data_value'];
            $price = new \Magento\Framework\DataObject($data);
            $price->setData(
                $this->getMetadataPool()->getMetadata(ProductInterface::class)->getLinkField(),
                $productId
            );
            $this->_getResource()->savePriceData($price);
        }

        $productId = $object->getData($this->getMetadataPool()->getMetadata(ProductInterface::class)->getLinkField());
        $this->_getResource()->deletePriceData($productId);
        $this->_getResource()->saveMatrixData($productId, $new);
    }

}
