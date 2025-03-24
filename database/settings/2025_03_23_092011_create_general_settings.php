<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.name', 'your name');
        $this->migrator->add('general.title', 'yout title');
        $this->migrator->add('general.bio', 'your bio');
        $this->migrator->add('general.avatar', null);
        $this->migrator->add('general.skills', []);
        $this->migrator->add('general.socials', []);
    }
};
