@extends(backpack_view('blank'))

@section('content')
    @php
        Backpack\CRUD\app\Library\Widget::add()->to('before_content')->type('div')->class('row')->content([
            Backpack\CRUD\app\Library\Widget::make([
                'type'       => 'chart',
                'controller' => \App\Http\Controllers\Admin\Charts\DailyUsersChartController::class,

                // OPTIONALS
                'class'   => 'card mb-2',
                'wrapper' => ['class'=> 'col-md-6'] ,
                'content' => [
                     'header' => 'New Users (last 30)'
                 ],
            ]),
            Backpack\CRUD\app\Library\Widget::make([
                'type'       => 'chart',
                'controller' => \App\Http\Controllers\Admin\Charts\DailyLoadoutsChartController::class,

                // OPTIONALS
                'class'   => 'card mb-2',
                'wrapper' => ['class'=> 'col-md-6'] ,
                'content' => [
                     'header' => 'New Loadouts (last 30)'
                 ],
            ])
        ]);
    @endphp
@endsection
