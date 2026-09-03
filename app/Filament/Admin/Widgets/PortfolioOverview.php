<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ContactMe;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Stack;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Displays a high-level overview of portfolio administration data.
 */
class PortfolioOverview extends BaseWidget
{
    /**
     * Get the statistics displayed on the dashboard.
     *
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Active Projects',
                Project::query()
                    ->where('is_active', true)
                    ->count()
            )
                ->description(
                    Project::query()->count() . ' total projects'
                )
                ->icon('heroicon-o-briefcase'),

            Stat::make(
                'Active Skills',
                Skill::query()
                    ->where('is_active', true)
                    ->count()
            )
                ->description(
                    Skill::query()->count() . ' total skills'
                )
                ->icon('heroicon-o-code-bracket'),

            Stat::make(
                'Tech Stacks',
                Stack::query()->count()
            )
                ->description('Technologies used in the portfolio')
                ->icon('heroicon-o-cpu-chip'),

            Stat::make(
                'Messages',
                ContactMe::query()->count()
            )
                ->description('Received contact messages')
                ->icon('heroicon-o-envelope'),

            Stat::make(
                'Users',
                User::query()->count()
            )
                ->description('Administrative users')
                ->icon('heroicon-o-users'),
        ];
    }
}
