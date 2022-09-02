<?php

namespace Powerlifting;

use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;

/**
 * Task to clear and reset the records
 * This can be a bit flaky on low spec hosting services so I have added
 * paramaters to try and break the task up which are described below
 * truncate: Just clear all records and do nothing else
 * liftType: Limit to processing a certain lift type
 * id: Specify a Result ID to start from
 * limit: Limit the results to be processed
 */
class ClearAndResetRecordsTask extends BuildTask
{
    private static $segment = 'ClearAndResetRecordsTask';

    protected $title = 'Clear all the records and redo them';

    protected $description = 'This will truncate all the records then go through ' .
        'the results and set them up again';
    
    public function run($request) {
        $truncateOnly = $request->getVar('truncate');
        $liftType = $request->getVar('liftType');
        $fromID = $request->getVar('id');
        $limit = $request->getVar('limit');

        $filter['Active'] = 1;
        // truncate current records
        if ($liftType) {
            $filter['LiftTypeID'] = (int)$liftType;
        } else {
            DB::query('TRUNCATE Record');
        }
        if ($truncateOnly) {
            return;
        }
        if ($fromID) {
            $filter['ID:GreaterThan'] = $fromID;
        }

        // get all the results ordered by oldest date first
        if ($limit) {
            $results = Result::get()
                ->filter($filter)
                ->limit($limit)
                ->sort('DateOfLift', 'ASC');
        } else {
            $results = Result::get()
                ->filter($filter)
                ->sort('DateOfLift', 'ASC');
        }

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