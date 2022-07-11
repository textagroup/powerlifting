<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;

class LifterClass extends DataObject 
{
    private static $table_name = 'LifterClass';

    private static $db = [
        'Title' => 'Varchar',
        'MinWeight' => 'Int',
        'MaxWeight' => 'Int',
        'MinAge' => 'Int',
        'MaxAge' => 'Int',
        'Gender' => 'Enum("M, F, U")',
        'StandardSquat' => 'Decimal',
        'StandardBench' => 'Decimal',
        'StandardDeadlift' => 'Decimal',
        'StandardTotal' => 'Decimal',
        'Active' => 'Boolean',
    ];

    private static $has_one = [
        'Affiliation' => Affiliation::class
    ];

    private static $has_many = [
        'Override' => LifterClass::class
    ];
}


