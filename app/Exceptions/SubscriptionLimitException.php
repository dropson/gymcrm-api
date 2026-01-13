<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class SubscriptionLimitException extends Exception
{
    public function __construct(
        public string $resource,
        public ?int $limit,
        public string $plan
    ) {
        parent::__construct(
            sprintf('You have reached the limit for %s. Upgrade your plan.', $resource)
        );
    }
}
