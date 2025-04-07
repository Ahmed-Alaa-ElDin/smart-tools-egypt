<?php

namespace App\Services\Front\Deliveries;

use App\Interfaces\Front\Deliveries\DeliveryInterface;

class DeliveryService
{
    public function __construct(private DeliveryInterface $deliveryCompany)
    {
    }

    public function getAWBs(array $deliveryIds, string $pageSize = "A6", string $language = "ar"): string
    {
        return $this->deliveryCompany->getAWBs($deliveryIds, $pageSize, $language);
    }
}
