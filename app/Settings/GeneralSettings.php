<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $name;

    public string $title;

    public string $bio;

    public ?string $avatar;

    public array $skills;

    public array $socials;

    public static function group(): string
    {
        return 'general';
    }
}
