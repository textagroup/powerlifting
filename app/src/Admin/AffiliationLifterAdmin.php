<?php

namespace Powerlifting;

use SilverStripe\Admin\ModelAdmin;

class AffiliationLifterAdmin extends ModelAdmin 
{

    private static $managed_models = [
        Affiliation::class,
        Lifter::class,
        LiftType::class
    ];

    private static $url_segment = 'affiliate-lifter';

    private static $menu_title = 'Afiliates, Lifters and Lift types';
}
