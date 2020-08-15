<?php

namespace App\Http\Controllers\Admin\Charts;

use App\Loadout;
use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/**
 * Class DailyLoadoutsChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DailyLoadoutsChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        // MANDATORY. Set the labels for the dataset points
        $labels = [];
        for ($days_backwards = 30; $days_backwards >= 0; $days_backwards--) {
            if ($days_backwards == 1) {
            }
            $labels[] = $days_backwards.' days ago';
        }
        $this->chart->labels($labels);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/daily-loadouts'));

        // OPTIONAL
        // $this->chart->minimalist(false);
        // $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
     public function data()
     {
         $loadouts = [];
         // TODO: There is a better way to do this, but we can optimize later
         for ($days_backwards = 30; $days_backwards >= 0; $days_backwards--) {
             // Could also be an array_push if using an array rather than a collection.
             $loadouts[] = Loadout::whereDate('created_at', today()->subDays($days_backwards))
                 ->count();
         }

         $this->chart->dataset('Loadouts', 'line', $loadouts)
             ->color('rgb(77, 189, 116)')
             ->backgroundColor('rgba(77, 189, 116, 0.4)');
     }
}
