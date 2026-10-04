<?php

namespace Tests\Unit;

use App\Enums\Stripe\ProductIntervalEnum;
use App\Enums\Stripe\SubscriptionStatusEnum;
use App\Enums\TenantSuport\TicketPriorityEnum;
use App\Enums\TenantSuport\TicketStatusEnum;
use App\Enums\TenantSuport\TicketTypeEnum;

use PHPUnit\Framework\TestCase;

class TenantForgeDomainEnumTest extends TestCase
{
    public function test_subscription_status_values_are_unique(): void
    {
        $values = array_map(static fn ($case) => $case->value, SubscriptionStatusEnum::cases());

        $this->assertNotEmpty($values);
        $this->assertSame($values, array_values(array_unique($values)));
    }

    public function test_product_interval_values_are_unique(): void
    {
        $values = array_map(static fn ($case) => $case->value, ProductIntervalEnum::cases());

        $this->assertNotEmpty($values);
        $this->assertSame($values, array_values(array_unique($values)));
    }

    public function test_support_ticket_states_are_explicit_and_unique(): void
    {
        foreach ([TicketPriorityEnum::class, TicketStatusEnum::class, TicketTypeEnum::class] as $enum) {
            $values = array_map(static fn ($case) => $case->value, $enum::cases());

            $this->assertNotEmpty($values, $enum . ' must define at least one state.');
            $this->assertSame(
                $values,
                array_values(array_unique($values)),
                $enum . ' must not contain duplicate persisted values.'
            );
        }
    }
}
