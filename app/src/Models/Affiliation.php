<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;

class Affiliation extends DataObject 
{
    private static $table_name = 'Afiliation';

    private static $db = [
        'Title' => 'Varchar',
        'Description' => 'Text',
        'Active' => 'Boolean',
    ];
}


