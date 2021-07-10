<?php

use Oxygen\Crud\BlueprintTrait\PreviewableCrudTrait;
use Oxygen\Crud\BlueprintTrait\PublishableCrudTrait;
use Oxygen\Crud\BlueprintTrait\VersionableCrudTrait;
use OxygenModule\Events\Controller\UpcomingEventsController;
use Oxygen\Crud\BlueprintTrait\SearchableCrudTrait;

Blueprint::make('UpcomingEvent', function(Oxygen\Core\Blueprint\Blueprint $blueprint) {
    $blueprint->setController(UpcomingEventsController::class);
    $blueprint->setDisplayName('Event');
    $blueprint->setPluralDisplayName('Events');
    $blueprint->setIcon('calendar');

    $blueprint->setToolbarOrders([
        'section' => [
            'getList.search', 'getCreate', 'getTrash'
        ],
        'item' => [
            'postPublish',
            'getPreview',
            'getUpdate,More' => ['getInfo', 'postNewVersion', 'postMakeHeadVersion', 'deleteDelete', 'postRestore', 'deleteForce']
        ],
        'versionList' => [
            'deleteVersions'
        ]
    ]);

    $blueprint->useTrait(new PreviewableCrudTrait());
    $blueprint->useTrait(new VersionableCrudTrait());
    $blueprint->useTrait(new PublishableCrudTrait());
    $blueprint->useTrait(new SearchableCrudTrait());
});
