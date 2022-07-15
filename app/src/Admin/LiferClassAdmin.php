<?php

namespace Powerlifting;

use SilverStripe\Admin\ModelAdmin;

class LifterClassAdmin extends ModelAdmin 
{

    private static $managed_models = [
        LifterClass::class
    ];

    private static $url_segment = 'lifter-class';

    private static $menu_title = 'Lifter Class';
}
