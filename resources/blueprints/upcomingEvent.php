<?php

use Oxygen\Crud\BlueprintTrait\PreviewableCrudTrait;
use Oxygen\Crud\BlueprintTrait\PublishableCrudTrait;
use Oxygen\Crud\BlueprintTrait\VersionableCrudTrait;
use OxygenModule\Events\Controller\UpcomingEventsController;
use Oxygen\Crud\BlueprintTrait\SearchableCrudTrait;

Blueprint::make('UpcomingEvent', function($blueprint) {
    $blueprint->setController(UpcomingEventsController::class);
    $blueprint->setIcon('calendar');

    $blueprint->setToolbarOrders([
        'section' => [
            'getList.search', 'getCreate', 'getTrash'
        ],
        'item' => [
            'getPreview',
            'getUpdate,More' => ['postPublish', 'getInfo', 'deleteDelete', 'postRestore', 'deleteForce'],
            'Version' => ['postMakeDraft', 'postNewVersion', 'postMakeHeadVersion']
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
