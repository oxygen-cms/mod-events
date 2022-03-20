<?php

use Oxygen\Preferences\Loader\PreferenceRepositoryInterface;
use Oxygen\Preferences\Loader\DatabaseLoader;
use Oxygen\Preferences\ThemeSpecificPreferencesFallback;
use Oxygen\Core\Theme\ThemeManager;

Preferences::register('appearance.events', function($schema) {
    $schema->setTitle('Upcoming Events');
    $schema->setLoader(
        new DatabaseLoader(
            app(PreferenceRepositoryInterface::class),
            'appearance.events',
            new ThemeSpecificPreferencesFallback(app(ThemeManager::class), 'appearance.events')
        )
    );

    $schema->makeField([
        'name' => 'contentView',
        'label' => 'Standalone Content View',
        'validationRules' => ['nullable', 'view_exists']
    ]);
});
