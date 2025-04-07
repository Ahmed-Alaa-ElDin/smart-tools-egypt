<?php

namespace App\Interfaces\Front\Deliveries;

use App\Models\Order;

interface DeliveryInterface
{
    /**
     * TODO:: Create a new delivery
     */
    public function createDelivery(Order $order): array;

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
    public function getAWBs(array $deliveryIds, string $pageSize = "A6", string $language = "ar"): string;
}
