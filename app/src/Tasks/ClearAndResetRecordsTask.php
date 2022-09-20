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
            // do not truncate if we processing in sections
            if (!$fromID && !$limit) {
                DB::query('TRUNCATE Record');
            }
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

        // loop results and check if records need to be set
        $currentRecords = [];
        $setRecords = [];
        foreach ($results as $result) {
            $children = $result->LifterClass()->getLifterClassChildren();
            self::recordBroken($result, $class, $currentRecords[$id], $setRecords);
            if ($children) {
                foreach ($children as $id) {
                    $class = LifterClass::get_by_id($id);
                    if (!isset($currentRecords[$id])) {
                        $currentRecords[$id] = $class->getCurrentClassResults();
                    }
                    self::recordBroken($result, $class, $currentRecords[$id], $setRecords);
                }
            }
        }
        foreach (Record::$checkRecords as $checkRecord) {
            foreach ($setRecords[$checkRecord] as $id => $setRecord) {
                $record = new Record();
                $record->RecordType = $checkRecord;
                $record->Active = 1;
                $record->ResultID = $setRecord['Result'];
                $record->LifterClassID = $id;
                $record->write();
            }
        }
    }

    /**
     * Takes a result and checks it against a lifter class to see if it
     * constitues a record lift or total
     *
     * @param Result $result
     * @param LifterClass $lifterClass
     * @param array $currentRecords
     * @param array $setRecords
     *
     * @return void
     */
    public static function recordBroken($result, $lifterClass, &$currentRecords, &$setRecords)
    {
        $dateOfLift = strtotime($result->DateOfLift);
        foreach (Record::$checkRecords as $checkRecord) {
            if ($result->$checkRecord == 0) {
                continue;
            }
            // if result is equal or greater then the current or standard
            $record = null;
            // could be bench only so skip squat and deadlift
            if ($currentRecords[$checkRecord]['Weight'] == 0) {
                continue;
            }
            if ($result->$checkRecord >= $currentRecords[$checkRecord]['Weight']) {
                // if there is an existing record run further checks
                if ($currentRecords[$checkRecord]['ID'] != 0) {
                    // if weight equals the record check which one came first
                    if ($result->$checkRecord == $currentRecords[$checkRecord]['Weight']) {
                        if ($dateOfLift > $currentRecords[$checkRecord]['Time']) {
                            continue;
                        }
                    }
                }
                $currentRecords[$checkRecord]['Weight'];
                $currentRecords[$checkRecord]['Time'];
                $add = false;
                if (isset($setRecords[$checkRecord][$lifterClass->ID])) {
                    $weight = $setRecords[$checkRecord][$lifterClass->ID]['Weight'];
                    if ($result->$checkRecord >= $weight) {
                        $add = true;
                    }
                } else {
                    $add = true;
                }
                if ($add) {
                    $setRecords[$checkRecord][$lifterClass->ID] = [
                        'Weight' => $result->$checkRecord,
                        'Result' => $result->ID,
                    ];
                }
            }
        }
    }
}