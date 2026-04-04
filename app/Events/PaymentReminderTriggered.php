<?php

namespace App\Events;

use App\Models\Resident;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentReminderTriggered
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Resident $resident,
        public int $unpaidCount,
        public int $overdueCount,
    ) {
    }
}
