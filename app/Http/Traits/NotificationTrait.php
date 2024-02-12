<?php

namespace App\Http\Traits;

use App\Models\Requisition;
use App\Models\StockItem;
use App\Models\StockItemQuantity;
use Carbon\Carbon;

trait NotificationTrait{

    public function requisitionAlert($requisition)
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

    public function stockLevelAlerts()
    {
        $lowStockItems = StockItemQuantity::where('quantity', '<=', 5)->get();
        
        $alerts = [];
        foreach ($lowStockItems as $item) {
            $stockItem = StockItem::find($item->stock_item_id);
            if ($stockItem) {
                $alerts[] = "Low Stock Alert: the amount of ".$stockItem->name. "(s) is running low in stock. The current quantity is: " . $item->quantity;
            }
        }

        return $alerts;
    }

}