<?php

namespace App\Filament\Widgets;

use Flowframe\Trend\Trend;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\TrendValue;

class TransactionChart extends ChartWidget
{
    protected static ?string $heading = 'Transaction Chart';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    public ?string $filter = 'today';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        switch ($activeFilter) {
            case 'today':
                $data = Trend::model(Transaction::class)
                    ->between(start: now()->startOfDay(), end: now()->endOfDay())
                    ->perHour()
                    ->sum('total');
                break;
            case 'week':
                $data = Trend::model(Transaction::class)
                    ->between(start: now()->subDays(7), end: now())
                    ->perDay()
                    ->sum('total');
                break;
            case 'month':
                $data = Trend::model(Transaction::class)
                    ->between(start: now()->subDays(30), end: now())
                    ->perDay()
                    ->sum('total');
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Amount',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date)
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
