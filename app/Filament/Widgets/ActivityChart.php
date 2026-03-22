<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use App\Models\Thread;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Activity (Last 7 Days)';

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day);
        });

        $labels = $days->map(fn ($date) => $date->format('D'))->toArray();

        $threadData = [];
        $commentData = [];

        foreach ($days as $date) {
            $threadData[] = Thread::whereDate('created_at', $date)->count();
            $commentData[] = Comment::whereDate('created_at', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Threads',
                    'data' => $threadData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59,130,246,0.2)',
                ],
                [
                    'label' => 'Comments',
                    'data' => $commentData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16,185,129,0.2)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
