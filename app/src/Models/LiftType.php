<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;

class LiftType extends DataObject 
{
    private static $table_name = 'LiftType';

    private static $db = [
        'Title' => 'Varchar',
        'Description' => 'Text',
        'Active' => 'Boolean',
    ];
}


