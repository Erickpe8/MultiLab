<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Icons Sets
    |--------------------------------------------------------------------------
    |
    | Define default icon sets. Each set needs to provide a unique prefix, at
    | least one path and any optional defaults like classes or attributes.
    |
    */

    'sets' => [

        // 'default' => [
        //     'path' => 'resources/svg',
        //     'disk' => '',
        //     'prefix' => 'icon',
        //     'fallback' => '',
        //     'class' => '',
        //     'attributes' => [],
        // ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global Default Classes
    |--------------------------------------------------------------------------
    |
    | These classes will be applied to every icon rendered through the Blade
    | Icons factory unless overridden.
    |
    */

    'class' => '',

    /*
    |--------------------------------------------------------------------------
    | Global Default Attributes
    |--------------------------------------------------------------------------
    |
    | These attributes will be merged into every icon element unless a specific
    | icon set overrides them.
    |
    */

    'attributes' => [
        // 'width' => 50,
        // 'height' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Global Fallback Icon
    |--------------------------------------------------------------------------
    |
    | Fallback icon to render when a requested icon name cannot be found.
    |
    */

    'fallback' => '',

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    | Toggle the default Blade icon component or override its name.
    |
    */

    'components' => [

        'disabled' => false,

        'default' => 'icon',

    ],

];
