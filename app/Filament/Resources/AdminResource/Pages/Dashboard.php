<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Widgets\DailySalesWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $view = 'filament.resources.admin-resource.pages.dashboard';

    // ✅ Must be public
    public function getWidgets(): array
    {
        return [
            DailySalesWidget::class,
        ];
    }
}
