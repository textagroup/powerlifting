<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;

class LiftType extends DataObject 
{
    private static $table_name = 'LifrType';

    private static $db = [
        'Title' => 'Varchar',
        'Description' => 'Text',
        'Active' => 'Boolean',
    ];
}


