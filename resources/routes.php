<?php

Route::middleware('api_auth')->group(function() {
    Route::get('/oxygen/api/upcoming-events', '\OxygenModule\Events\Controller\UpcomingEventsController@getListApi')
        ->name('upcomingEvents.getListApi')
        ->middleware('oxygen.permissions:upcomingEvents.getList');

    Route::post('/oxygen/api/upcoming-events/search', '\OxygenModule\Events\Controller\UpcomingEventsController@findByTrybookingSessionId')
        ->name('upcomingEvents.findByTrybookingSessionId')
        ->middleware('oxygen.permissions:upcomingEvents.findByTrybookingSessionId');

    Route::put('/oxygen/api/upcoming-events/{id}', '\OxygenModule\Events\Controller\UpcomingEventsController@putUpdateApi')
        ->name('upcomingEvents.putUpdate')
        ->middleware('oxygen.permissions:upcomingEvents.putUpdate');
});