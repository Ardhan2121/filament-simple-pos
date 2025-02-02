<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionOverview extends BaseWidget
{
    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Transactions', Transaction::whereDate('created_at', now())->count()),
            Stat::make('Total Amount', 'IDR ' . number_format(Transaction::whereDate('created_at', now())->sum('total'), 0, ',', '.')),
        ];
    }
}
