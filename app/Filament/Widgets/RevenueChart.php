<?php

namespace App\Filament\Widgets;

use App\Models\Command;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Chiffre d\'affaires — 14 derniers jours';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(fn (int $i) => now()->subDays($i)->toDateString());

        $commands = Command::with('article')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->get();

        $itemsByDay = $commands->groupBy(fn (Command $command) => $command->created_at->toDateString());
        $ordersByDay = $commands->unique('group_id')->groupBy(fn (Command $command) => $command->created_at->toDateString());

        $revenue = $days->map(function (string $day) use ($itemsByDay, $ordersByDay) {
            $itemsTotal = $itemsByDay->get($day, collect())
                ->sum(fn (Command $command) => $command->quantity * ($command->article?->price ?? 0));

            $shippingTotal = $ordersByDay->get($day, collect())->sum('shipping_fee');

            return round($itemsTotal + $shippingTotal, 2);
        });

        return [
            'datasets' => [
                [
                    'label' => 'Chiffre d\'affaires (DT)',
                    'data' => $revenue->values(),
                    'borderColor' => '#4D5147',
                    'backgroundColor' => 'rgba(77, 81, 71, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $days->map(fn (string $day) => \Illuminate\Support\Carbon::parse($day)->locale('fr')->translatedFormat('j M'))->values(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
