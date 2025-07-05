<?php

namespace App\Enums;

enum OrderStatus: int
{
    // Pickup
    case PickupRequested = 10;
    case WaitingForRoute = 11;
    case RouteAssigned = 20;
    case PickedUpFromBusiness = 21;
    case PickingUpFromConsignee = 22;
    case PickedUpFromConsignee = 23;
    case ReceivedAtWarehouse = 24;

    // In Transit
    case InTransitBetweenHubs = 30;

    // Delivery
    case PickingUp = 40;
    case PickedUp = 41;
    case PendingCustomerSignature = 42;
    case DebriefedSuccessfully = 43;
    case Delivered = 45;
    case ReturnedToBusiness = 46;
    case Exception = 47;
    case Terminated = 48;
    case CanceledUncoveredArea = 49;
    case CollectionFailed = 50;

    // Other
    case Lost = 100;
    case Damaged = 101;
    case Investigation = 102;
    case AwaitingYourAction = 103;
    case Archived = 104;
    case OnHold = 105;

    // Order
    case UnderProcessing = 201;
    case Created = 202;
    case WaitingForPayment = 203;
    case ShippingCreates = 204;
    case Preparing = 205;
    case QualityChecked = 206;
    case Shipped = 207;
    case WaitingForApproval = 208;
    case Prepared = 209;
    case Approved = 210;
    case Rejected = 211;
    case WaitingForContact = 212;

    // Cancellation
    case CancellationRequested = 301;
    case CancellationApproved = 302;
    case CancellationRejected = 303;
    case UnderEditing = 304;
    case EditRequested = 305;
    case EditApproved = 306;
    case EditRejected = 307;

    // Return
    case UnderReturning = 401;
    case ReturnRequested = 402;
    case ReturnApproved = 403;
    case ReturnRejected = 404;
    case WaitingForRefund = 405;

    /**
     * Get the name of the enum case from its value.
     *
     * @param int $value
     * @return string|null
     */
    public static function getKeyFromValue(?int $value): ?string
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return preg_replace('/(?<!^)([A-Z])/', ' $1', $case->name); // Convert camel case to words
            }
        }
        return null; // Return null if no matching value is found
    }
}
