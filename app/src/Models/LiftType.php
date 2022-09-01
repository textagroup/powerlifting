<?php

namespace Powerlifting;

use SilverStripe\Forms\ListboxField;
use SilverStripe\ORM\DataObject;

class LiftType extends DataObject 
{
    private static $table_name = 'LiftType';

    private static $db = [
        'Title' => 'Varchar',
        'Description' => 'Text',
        'Active' => 'Boolean',
        'ExcludeLiftTypes' =>'Text',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        // used to specify lift types this class can not set records for
        $tagField = ListboxField::create(
            'ExcludeLiftTypes',
            'Exclude Lift Types',
            LiftType::get()->filter('Active', 1)->exclude('ID', $this->ID)
                ->map('ID', 'Title'),
            explode(',', $this->ExcludeLiftTypes)
        )->setDescription('Do not set records for these lift types');
        $fields->replaceField('ExcludeLiftTypes', $tagField);
        return $fields;
    }
}


