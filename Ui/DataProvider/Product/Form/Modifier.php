<?php
namespace Smile\Matrix\Ui\DataProvider\Product\Form;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;
use Magento\Framework\Stdlib\ArrayManager;

/**
 * Class Matrix
 */
class Modifier extends AbstractModifier
{
    const TARGET_ATTRIBUTE_CODE = "gender2";

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        ArrayManager $arrayManager
    ) {
        $this->arrayManager = $arrayManager;
    }

    /**
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->customizeField(self::TARGET_ATTRIBUTE_CODE);
        return $this->meta;
    }

    /**
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * Customize attribute code
     *
     * @return $this
     */
    private function customizeField($attributeCode)
    {
        $tierPricePath = $this->arrayManager->findPath(
            $attributeCode,
            $this->meta,
            null,
            'children'
        );

        if ($tierPricePath) {
            $this->meta = $this->arrayManager->merge(
                $tierPricePath,
                $this->meta,
                $this->getTierPriceStructure($tierPricePath)
            );
            $this->meta = $this->arrayManager->set(
                $this->arrayManager->slicePath($tierPricePath, 0, -3)
                . '/' . $attributeCode,
                $this->meta,
                $this->arrayManager->get($tierPricePath, $this->meta)
            );
            $this->meta = $this->arrayManager->remove(
                $this->arrayManager->slicePath($tierPricePath, 0, -2),
                $this->meta
            );
        }
        
        return $this;
    }

    /**
     * Get tier price dynamic rows structure
     *
     * @param string $tierPricePath
     * @return array
     */
    private function getTierPriceStructure($tierPricePath)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'dynamicRows',
                        'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows',
                        'label' => __('Matrix'),
                        'renderDefaultRecord' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => '',
                        'dndConfig' => [
                            'enabled' => false,
                        ],
                        'disabled' => false,
                        'required' => false,
                        'sortOrder' =>
                            $this->arrayManager->get($tierPricePath . '/arguments/data/config/sortOrder', $this->meta),
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'isTemplate' => true,
                                'is_collection' => true,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => [
                        
                        'data_name' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Name'),
                                        'dataScope' => 'data_name',
                                        'sortOrder' => 20,
                                    ],
                                ],
                            ],
                        ],
                        
                        'data_value' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Value'),
                                        'dataScope' => 'data_value',
                                        'sortOrder' => 30,
                                    ],
                                ],
                            ],
                        ],
                       
                        'actionDelete' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => 'actionDelete',
                                        'dataType' => Text::NAME,
                                        'label' => '',
                                        'sortOrder' => 50,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
