<?php

use App\Enums\OrderStatus;

return [
    "constants" => [
        'DEFAULT_PASSWORD' => 'Password@1234',
    ],
    "order_status_type" => [
        "bosta" => [
            OrderStatus::PickupRequested->value,
            OrderStatus::WaitingForRoute->value,
            OrderStatus::RouteAssigned->value,
            OrderStatus::PickedUpFromBusiness->value,
            OrderStatus::PickingUpFromConsignee->value,
            OrderStatus::PickedUpFromConsignee->value,
            OrderStatus::ReceivedAtWarehouse->value,
            OrderStatus::InTransitBetweenHubs->value,
            OrderStatus::PickingUp->value,
            OrderStatus::PickedUp->value,
            OrderStatus::PendingCustomerSignature->value,
            OrderStatus::DebriefedSuccessfully->value,
            OrderStatus::Delivered->value,
            OrderStatus::ReturnedToBusiness->value,
            OrderStatus::Exception->value,
            OrderStatus::Terminated->value,
            OrderStatus::CanceledUncoveredArea->value,
            OrderStatus::CollectionFailed->value,
            OrderStatus::Lost->value,
            OrderStatus::Damaged->value,
            OrderStatus::Investigation->value,
            OrderStatus::AwaitingYourAction->value,
            OrderStatus::Archived->value,
            OrderStatus::OnHold->value,
        ],
        "new_orders" => [
            OrderStatus::WaitingForPayment->value,
            OrderStatus::WaitingForApproval->value,
        ],
        "approved_orders" => [
            OrderStatus::Approved->value,
            OrderStatus::Preparing->value,
            OrderStatus::QualityChecked->value,
            OrderStatus::EditApproved->value,
        ],
        "ready_for_shipping_orders" => [
            OrderStatus::Prepared->value,
        ],
        "edited_orders" => [
            OrderStatus::UnderEditing->value,
            OrderStatus::EditRequested->value,
        ],
        "shipped_orders" => [
            OrderStatus::Shipped->value,
        ],
        "suspended_orders" => [
            OrderStatus::WaitingForContact->value,
            OrderStatus::WaitingForPayment->value,
        ],
        "delivered_orders" => [
            OrderStatus::Delivered->value,
        ],
    ],
    "order_status_options" => [
        "all_orders" => [
            OrderStatus::UnderProcessing->value,
            OrderStatus::Created->value,
            OrderStatus::WaitingForPayment->value,
            OrderStatus::ShippingCreates->value,
            OrderStatus::Preparing->value,
            OrderStatus::QualityChecked->value,
            OrderStatus::Shipped->value,
            OrderStatus::WaitingForApproval->value,
            OrderStatus::Prepared->value,
            OrderStatus::Approved->value,
            OrderStatus::Rejected->value,
            OrderStatus::WaitingForContact->value,
            OrderStatus::CancellationRequested->value,
            OrderStatus::CancellationApproved->value,
            OrderStatus::CancellationRejected->value,
            OrderStatus::UnderEditing->value,
            OrderStatus::EditRequested->value,
            OrderStatus::EditApproved->value,
            OrderStatus::EditRejected->value,
            OrderStatus::UnderReturning->value,
            OrderStatus::ReturnRequested->value,
            OrderStatus::ReturnApproved->value,
            OrderStatus::ReturnRejected->value,
            OrderStatus::WaitingForRefund->value,
            OrderStatus::Delivered->value,
        ],
        "new_orders" => [
            OrderStatus::WaitingForPayment->value,
            OrderStatus::Approved->value,
            OrderStatus::Rejected->value,
            OrderStatus::WaitingForContact->value,
        ],
        "approved_orders" => [
            OrderStatus::Prepared->value,
        ],
        "ready_for_shipping_orders" => [
            OrderStatus::Shipped->value,
        ],
        "edited_orders" => [
            OrderStatus::EditApproved->value,
            OrderStatus::EditRejected->value,
        ],
        "shipped_orders" => [
            OrderStatus::Delivered->value,
            OrderStatus::CancellationRequested->value,
        ],
        "suspended_orders" => [
            OrderStatus::Approved->value,
            OrderStatus::Rejected->value,
        ],
        "delivered_orders" => [
            OrderStatus::Delivered->value,
            OrderStatus::ReturnRequested->value,
            OrderStatus::ReturnApproved->value,
            OrderStatus::ReturnRejected->value,
        ],
    ]
];
