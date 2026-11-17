<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

abstract class DailyActivity extends ChartWidget
{
    protected string $activityModel;
    protected string $dateColumn = 'created_at';
    protected string $activityLabel;

    protected function getData(): array
    {
        $start = today()->subDays(30);
        $counts = $this->activityModel::query()
            ->where($this->dateColumn, '>=', $start)
            ->selectRaw('DATE('.$this->dateColumn.') as activity_date, COUNT(*) as aggregate')
            ->groupBy('activity_date')->pluck('aggregate', 'activity_date');
        $labels = [];
        $data = [];
        for ($day = 0; $day <= 30; $day++) {
            $date = $start->copy()->addDays($day)->toDateString();
            $labels[] = $date;
            $data[] = (int) ($counts[$date] ?? 0);
        }

        return ['labels' => $labels, 'datasets' => [['label' => $this->activityLabel, 'data' => $data]]];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
