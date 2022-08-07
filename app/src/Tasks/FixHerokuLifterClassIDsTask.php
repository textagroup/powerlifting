<?php

namespace Powerlifting;

use SilverStripe\Dev\BuildTask;
//use SilverStripe\ORM\DB;

class FixHerokuLifterClassIDsTask extends BuildTask
{
    private static $segment = 'FixHerokuLifterClassIDsTask';

    protected $title = 'Fix override relationship IDs';

    protected $description = 'ClearDB primary keys are not sequential this task' .
        ' fixes the data in the Override column when it is imported via a CSV file';
    
    public function run($request) {
        // first of all check the current IDs are not sequential
        $nonSequentialIDs = 0;
        $lifterClasses = LifterClass::get()->sort('ID', 'ASC');
        $i = 0;
        $ids = [];
        foreach ($lifterClasses as $lifterClass) {
            $i++;
            if ($lifterClass->ID != $i) {
                $nonSequentialIDs++;
            }
            $ids[$i] = $lifterClass->ID;
        }
        // something is not right
        if ($nonSequentialIDs != $i) {
            echo 'IDs are not following suspected format' . PHP_EOL;
            return;
        }

        // check the first 2 IDS are 4 and 14
        if ($ids[1] != 4 && $ids[2] != 14) {
            echo 'The first couple of IDs are not in the ClearDB primary key format' . PHP_EOL;
            return;
        }

        // OK if we get here we can start fixing the Override IDs
        $updated = 0;
        foreach ($lifterClasses->where('Override IS NOT NULL') as $lifterClass) {
            $cleanIDs = [];
            $dirtyIDs = json_decode($lifterClass->Override, true);
            if (isset($dirtyIDs) && count($dirtyIDs)) {
                foreach ($dirtyIDs as $id) {
                    $cleanIDs[] = $ids[$id];
                }
            }
            if (count($cleanIDs)) {
                $updated++;
                $lifterClass->Override = json_encode($cleanIDs);
                $lifterClass->write();
            }
        }
        echo $updated . ' records updated';
    }
}