<?php

namespace Powerlifting;

use SilverStripe\Admin\ModelAdmin;

class RecordAdmin extends ModelAdmin 
{

    private static $managed_models = [
        Record::class
    ];

    private static $url_segment = 'record';

    private static $menu_title = 'Records';
}
