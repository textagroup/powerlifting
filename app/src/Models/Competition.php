<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;

class Competition extends DataObject 
{
    private static $table_name = 'Competition';

    private static $db = [
        'Title' => 'Varchar',
        'Description' => 'Text',
        'Active' => 'Boolean',
    ];

    private $static_one = [
        'Region' => Region::class,
        'Affiliation' => Affiliation::class
    ];
}


