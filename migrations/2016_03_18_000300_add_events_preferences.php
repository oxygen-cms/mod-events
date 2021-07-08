<?php

use Illuminate\Database\Migrations\Migration;
use Oxygen\Preferences\Loader\PreferenceRepositoryInterface;
use Oxygen\Preferences\Repository;

class AddEventsPreferences extends Migration {

    /**
     * Run the migrations.
     */
    public function up() {
        $preferences = App::make(PreferenceRepositoryInterface::class);

        $item = $preferences->make();
        $item->setKey('appearance.events');
        $data = new Repository([]);
        $data->set('contentView', 'oxygen/crud::content.content');
        $item->setPreferences($data);
        $preferences->persist($item, false);

        $preferences->flush();
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        $preferences = App::make(PreferenceRepositoryInterface::class);

        $preferences->delete($preferences->findByKey('appearance.events'), false);

        $preferences->flush();
    }
}
