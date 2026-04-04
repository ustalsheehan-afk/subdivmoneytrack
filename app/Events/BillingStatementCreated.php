<?php

namespace App\Events;

use App\Models\Due;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BillingStatementCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Due $due)
    {
    }
}
