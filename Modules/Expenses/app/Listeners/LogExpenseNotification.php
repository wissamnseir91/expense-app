<?php

namespace Modules\Expenses\app\Listeners;

use Modules\Expenses\app\Events\ExpenseCreated;
use Illuminate\Support\Facades\Log;

class LogExpenseNotification
{
    /**
     * Handle the ExpenseCreated event
     */
    public function handle(ExpenseCreated $event): void
    {
        $this->logNotification($event->expense, 'created');
    }
    /**
     * Log notification to a file
     */
    private function logNotification($expense, $action): void
    {
        Log::info("Expense {$action}: {$expense->title} - Amount: {$expense->amount}");
    }
}
