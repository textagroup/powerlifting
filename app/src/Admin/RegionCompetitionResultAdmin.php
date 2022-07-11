<?php

namespace Powerlifting;

use SilverStripe\Admin\ModelAdmin;

class RegionCompetitionResultAdmin extends ModelAdmin 
{

    private static $managed_models = [
        Region::class,
        Competition::class,
        Result::class
    ];

    private static $url_segment = 'region-competition-result';

    private static $menu_title = 'Region, Competitions and Results';
}
