<?php
namespace Smile\Matrix\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Observer model
 */
class SaveMatrixBackend implements ObserverInterface
{

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Framework\DataObject $response */
        $attribute = $observer->getEvent()->getAttribute();
        if ($attribute->getFrontendInput() == "dynamicRows") {
            if (!$attribute->getBackendModel()) {
                $attribute->setBackendModel(\Magento\Eav\Model\Entity\Attribute\Backend\JsonEncoded::class);
            }
            if (!$attribute->getBackendType()) {
                $attribute->setBackendType("json");
            }
        }
    }
}
