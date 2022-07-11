<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;

class Region extends DataObject 
{
    private static $table_name = 'Region';

    private static $db = [
        'Title' => 'Varchar',
        'Active' => 'Boolean',
    ];
}


