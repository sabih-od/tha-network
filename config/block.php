<?php

declare(strict_types=1);

use LaravelInteraction\Block\Block;

return [
    'load_migrations' => true,
    'uuids' => false,
    'models' => [
        'user' => \App\Models\User::class,
        'block' => Block::class,
    ],
    'table_names' => [
        'blocks' => 'blocks',
    ],
    'column_names' => [
        'user_foreign_key' => 'user_id',
    ],
    /*
    |--------------------------------------------------------------------------
    | Default Divisors For Humans
    |--------------------------------------------------------------------------
    |
    | Divisors: Thousand, Million, Billion, Trillion, Quadrillion, Quintillion
    | Shorthands: "K", "M", "B", "T", "Qa", "Qi"
    |
    */
    'divisors' => [
        1000 ** 0 => '',
        1000 ** 1 => 'K',
        1000 ** 2 => 'M',
        1000 ** 3 => 'B',
        1000 ** 4 => 'T',
        1000 ** 5 => 'Qa',
        1000 ** 6 => 'Qi',
    ],
];
