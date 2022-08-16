<?php

namespace Powerlifting;

use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;

class ClearAndResetRecordsTask extends BuildTask
{
    private static $segment = 'ClearAndResetRecordsTask';

    protected $title = 'Clear all the records and redo them';

    protected $description = 'This will truncate all the records then go through ' .
        'the results and set them up again';
    
    public function run($request) {
        // truncate current records
        DB::query('TRUNCATE Record');

        // get all the results ordered by oldest date first
        $results = Result::get()
            ->filter('Active', 1)
            ->sort('DateOfLift', 'ASC');

        // loop resutls and check if records need to be set
        foreach ($results as $result) {
            Record::recordBroken($result, $result->LifterClass());
            $children = $result->LifterClass()->getLifterClassChildren();
            if ($children) {
                foreach ($children as $id) {
                    $class = LifterClass::get_by_id($id);
                    Record::recordBroken($result, $class);
                }
            }
        }
    }
}