<?php

namespace App\Models;

class NavigationItem
{
    public string $name, $route, $before;

    /**
     * Language constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * @param array $attributes
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $attribute) {
            $this->{$key} = $attribute;
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function all(): \Illuminate\Support\Collection
    {
        $array = [
            new self(['name' => 'Homepage', 'route' => 'home']),
            new self(['name' => 'X', 'route' => 'x', 'before' => '<i class="fa-brands fa-x-twitter me-2"></i>']),
            new self(['name' => 'Twitch', 'route' => 'twitch', 'before' => '<i class="fa-brands fa-twitch me-2"></i>', 'after' => '<div class="nav-item-flag-dot twitch-live-indicator ms-2 ' . (TwitchStream::first() ? '' : 'd-none') . '"></div>']),
        ];

        $collection = collect($array);

        return $collection;
    }
}
