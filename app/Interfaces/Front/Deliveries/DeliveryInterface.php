<?php

namespace App\Interfaces\Front\Deliveries;

interface DeliveryInterface
{
    /**
     * TODO:: Create a new delivery
     */

    /**
     * TODO:: Update delivery status
     */

    /**
     * TODO:: Get delivery status
     */

    /**
     * Get delivery Purchase Order
     * @param array $deliveryIds
     * @param string $pageSize
     * @param string $language
     * @return string
     */
    public function getAWBs(array $deliveryIds, string $pageSize = "A4", string $language = "ar"): string;
}
