<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;
//use SilverStripe\Versioned\Versioned;

class Lifter extends DataObject 
{
    private static $table_name = 'Lifter';

/*
    private static $extensions = [
        Versioned::class . '.versioned',
    ];
*/

    private static $db = [
        'Title' => 'Varchar',
        'Gender' => 'Enum("M, F, U")',
        'DateOfBirth' => 'Date',
        'LifterLink' => 'Varchar',
        'Active' => 'Boolean',
    ];

    private static $summary_fields = [
        'Title',
        'Gender',
        'DateOfBirth',
        'RecordLink',
        'Active'
    ];

    protected function getRecordLink() {
        return 'https://openpowerlifting.org/' . $this->LifterLink;
    }
}


