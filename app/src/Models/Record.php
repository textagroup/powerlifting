<?php

namespace Powerlifting;

use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;

class Record extends DataObject 
{
    private static $table_name = 'Record';

    private static $extensions = [
        Versioned::class . '.versioned',
    ];

    private static $db = [
        'RecordType' => 'Enum("Squat, Bench, Deadlift, Total")',
        'Active' => 'Boolean',
    ];

    private static $has_one = [
        'LifterClass' => LifterClass::class,
        'Result' => Result::class
    ];

    private static $summary_fields = [
        'Result.Lifter.Title' => 'Title',
        'LifterClass.Title' => 'Class',
        'RecordType' => 'Record',
        'Weight' => 'Weight',
        'Active'
    ];

    public static $checkRecords = [
        'Squat',
        'Bench',
        'Deadlift',
        'Total'
    ];

    /**
     * Takes a result and checks it against a lifter class to see if it
     * constitues a record lift or total
     *
     * @param Result $result
     * @param LifterClass $lifterClass
     *
     * @return void
     */
    public static function recordBroken($result, $lifterClass)
    {
        $dateOfLift = strtotime($result->DateOfLift);
        foreach (self::$checkRecords as $checkRecord) {
            // need to refetch the current records for each iteration
            // as adding a record in a previous iteration will mean they
            // could already be mout of date
            $currentRecords = $lifterClass->getCurrentRecords();
            // if result is equal or greater then the current or standard
            $record = null;
            if ($result->$checkRecord >= $currentRecords[$checkRecord]['Weight']) {
                // if there is an existing record run further checks
                if ($currentRecords[$checkRecord]['ID'] != 0) {
                    // if weight equals the record check which one came first
                    if ($result->$checkRecord == $currentRecords[$checkRecord]['Weight']) {
                        if ($dateOfLift > $currentRecords[$checkRecord]['Time']) {
                            continue;
                        }
                    }
                    $record = self::getRecordByResult($result, $lifterClass, $checkRecord);
                }
                if (is_null($record)) {
                    $record = new Record();
                }
                $record->RecordType = $checkRecord;
                $record->Active = 1;
                $record->ResultID = $result->ID;
                $record->LifterClassID = $lifterClass->ID;
                $record->write();
            }
        }
    }

    /*
     * Retrieve record for a result
     *
     * @param Result::class $result
     * @param LifterClass::class $result
     * @param String $recordType
     * @return mixed Record::class|null
     */
    public static function getRecordByResult($result, $lifterClass, $recordType)
    {
        $record = Record::get()
            ->filter([
                'ResultID:not' => $result->ID,
                'LifterClassID' => $lifterClass->ID,
                'RecordType' => $recordType
            ]);
        if ($record && $record->exists()) {
            return $record->first();
        }
        return null;
    }

    public function getWeight()
    {
        $result = $this->Result();
        switch ($this->RecordType) {
            case 'Squat':
                return $result->Squat;
            case 'Bench':
                return $result->Bench;
            case 'Deadlift':
                return $result->Deadlift;
            case 'Total':
                return $result->Total;
        }
    }
}
