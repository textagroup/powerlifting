<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;

class LiftType extends DataObject 
{
    private static $table_name = 'LifrType';

    private static $db = [
        'Title' => 'Varchar',
        'Active' => 'Boolean',
    ];
}


