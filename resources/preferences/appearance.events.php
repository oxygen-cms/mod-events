<?php

use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Preferences\Loader\DatabaseLoader;

Preferences::register('appearance.events', function($schema) {http://localhost/
    $schema->setTitle('Upcoming Events');
    $schema->setLoader(new DatabaseLoader(app(PreferenceRepositoryInterface::class), 'appearance.events'));

    $schema->makeFields([
        '' => [
            '' => [
                [
                    'name' => 'contentView',
                    'label' => 'Standalone Content View',
                    'validationRules' => ['nullable', 'view_exists']
                ]
            ]
        ]
    ]);
});
