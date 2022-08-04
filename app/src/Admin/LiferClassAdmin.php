<?php

namespace Powerlifting;

use SilverStripe\Admin\ModelAdmin;

class LifterClassAdmin extends ModelAdmin 
{

    private static $managed_models = [
        LifterClass::class
    ];

    private static $url_segment = 'lifter-class';

    private static $menu_title = 'Lifter Class';

    public function getExportFields()
    {
        return [
            'Title' => 'Title',
            'MinWeight' => 'MinWeight',
            'MaxWeight' => 'MaxWeight',
            'MinAge' => 'MinAge',
            'MaxAge' => 'MaxAge',
            'Gender' => 'Gender',
            'StandardSquat' => 'StandardSquat',
            'StandardBench' => 'StandardBench',
            'StandardDeadlift' => 'StandardDeadlift',
            'StandardTotal' => 'StandardTotal',
            'Active' => 'Active',
            'Override' => 'Override',
            'AffiliationID' => 'Affiliation',
            'LiftTypeID' => 'LiftType',
        ];
    }
}
