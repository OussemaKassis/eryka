<?php

namespace App\Filament\Widgets;

use App\Models\Command;
use App\Models\ContactMessage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrdersOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $orderCount = Command::distinct('group_id')->count('group_id');
        $messageCount = ContactMessage::count();

        $bestSeller = Command::query()
            ->selectRaw('article_id, SUM(quantity) as total_quantity')
            ->groupBy('article_id')
            ->orderByDesc('total_quantity')
            ->with('article')
            ->first();

        $bestSellerLabel = $bestSeller?->article
            ? "{$bestSeller->article->title} ({$bestSeller->total_quantity} vendus)"
            : 'Aucune vente pour le moment';

        return [
            Stat::make('Commandes', $orderCount),
            Stat::make('Messages reçus', $messageCount),
            Stat::make('Meilleure vente', $bestSellerLabel),
        ];
    }
}
