<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;

class Lifter extends DataObject 
{
    private static $table_name = 'Lifter';

    private static $db = [
        'Title' => 'Varchar',
        'Gender' => 'Enum("M, F, U")',
        'DateOfBirth' => 'Date',
        'OpenPowerlifting' => 'Varchar',
        'Active' => 'Boolean',
    ];
}


