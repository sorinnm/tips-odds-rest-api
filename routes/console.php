<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('top:import-fixtures')
    ->timezone('Europe/Bucharest')
    ->withoutOverlapping()
    ->sendOutputTo(storage_path('tipsOddsPredictions' . DIRECTORY_SEPARATOR . 'importFixtures-' . date('Y-m-d H:i:s') . '.log'))
    ->emailOutputTo(['sorinmihaiparvu@gmail.com', 'dan_lopataru@yahoo.com'])
    ->description("Imported fixtures from API-Football")
    ->dailyAt('07:00');
