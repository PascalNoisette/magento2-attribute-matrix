<?php
namespace Smile\Matrix\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Observer model
 */
class AddMatrixAttributeType implements ObserverInterface
{

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Framework\DataObject $response */
        $response = $observer->getEvent()->getResponse();
        $types = $response->getTypes();
        $types[] = [
            'value' => 'dynamicRows',
            'label' => __('dynamicRows'),
        ];

        $response->setTypes($types);
    }
}
