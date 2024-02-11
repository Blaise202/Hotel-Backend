<?php

namespace App\Http\Traits;

use App\Models\Requisition;
use Carbon\Carbon;

trait NotificationTrait{

    public function getNotification($requisition)
    {
        $today = Carbon::now();
        $deadline = $requisition->expected_deadline;
        $timeLeft = $today->diffInDays($deadline);

        if ($timeLeft <= 5 && $timeLeft > 0) {
            return 'The deadline for this requisition is ' . $timeLeft . ' days close';
        } elseif ($timeLeft < 0) {
            return 'The deadline for this requisition has already passed ' . abs($timeLeft) . ' day(s) ago';
        } elseif ($timeLeft == 0) {
            return 'The deadline for this requisition is today';
        }
    }


}