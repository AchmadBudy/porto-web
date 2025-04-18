<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $name;

    public string $title;

    public string $bio;

    public ?string $avatar;

    /** @var array<string> $skills*/
    public array $skills;

    /** @var array<string> $socials*/
    public array $socials;

    public static function group(): string
    {
        return 'general';
    }
}
