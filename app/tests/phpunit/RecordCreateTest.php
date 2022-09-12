<?php

use SilverStripe\Dev\SapphireTest;
use Powerlifting\Competition;
use Powerlifting\Lifter;
use Powerlifting\LifterClass;
use Powerlifting\LiftType;
use Powerlifting\Record;
use Powerlifting\Result;

class RecordCreateTest extends SapphireTest
{
    protected static $fixture_file = 'RecordCreateTest.yml';

    private $compResults = [
        [
            'Age' => 30,
            'DateOfLift' => '2021-08-15 12:00:00',
            'Gender' => 'M',
            'Squat' => '181',
            'Bench' => '140',
            'Deadlift' => '230',
            'Total' => '551',
            'Active' => true,
        ],
        [
            'Age' => 31,
            'DateOfLift' => '2021-09-15 12:00:00',
            'Gender' => 'M',
            'Squat' => '160',
            'Bench' => '150',
            'Deadlift' => '250',
            'Total' => '560',
            'Active' => true,
        ],
        [
            'Age' => 40,
            'DateOfLift' => '2021-10-15 12:00:00',
            'Gender' => 'M',
            'Squat' => '200',
            'Bench' => '165',
            'Deadlift' => '250',
            'Total' => '615',
            'Active' => true,
        ],
    ];

    public function testStandardRecords()
    {
        $lifterClass = LifterClass::get()
            ->filter('Title','94KG Open Male Raw')
            ->first();
        $records = $lifterClass->getCurrentClassResults();
        $this->assertEquals($records['Squat']['Weight'], 180);
        $this->assertEquals($records['Bench']['Weight'], 140);
        $this->assertEquals($records['Deadlift']['Weight'], 230);
        $this->assertEquals($records['Total']['Weight'], 550);
    }

    public function testAddingResults()
    {
        $lifter = $this->objFromFixture(Lifter::class, 'lifterOpen1');
        $lifterClass = LifterClass::get()
            ->filter('Title','94KG Open Male Raw')
            ->first();
        $liftType = LiftType::get()->first();
        $competition = Competition::get()->first();
        $result = new Result();
        $result->Age = $this->compResults[0]['Age'];
        $result->DateOfLift = $this->compResults[0]['DateOfLift'];
        $result->Gender = $this->compResults[0]['Gender'];
        $result->Squat = $this->compResults[0]['Squat'];
        $result->Bench = $this->compResults[0]['Bench'];
        $result->Deadlift = $this->compResults[0]['Deadlift'];
        $result->Total = $this->compResults[0]['Total'];
        $result->Active = 1;
        $result->CompetitionID = $competition->ID;
        $result->LifterID = $lifter->ID;
        $result->LifterClassID = $lifterClass->ID;
        $result->LiftTypeID = $liftType->ID;
        $result->write();

        $records = $lifterClass->getCurrentClassResults();
        $this->assertEquals($records['Squat']['Weight'], 181);
        $this->assertEquals($records['Bench']['Weight'], 140);
        $this->assertEquals($records['Deadlift']['Weight'], 230);
        $this->assertEquals($records['Total']['Weight'], 551);

        $lifter = $this->objFromFixture(Lifter::class, 'lifterOpen2');
        $liftType = LiftType::get()->first();
        $competition = Competition::get()->first();
        $result = new Result();
        $result->Age = $this->compResults[1]['Age'];
        $result->DateOfLift = $this->compResults[1]['DateOfLift'];
        $result->Gender = $this->compResults[1]['Gender'];
        $result->Squat = $this->compResults[1]['Squat'];
        $result->Bench = $this->compResults[1]['Bench'];
        $result->Deadlift = $this->compResults[1]['Deadlift'];
        $result->Total = $this->compResults[1]['Total'];
        $result->Active = 1;
        $result->CompetitionID = $competition->ID;
        $result->LifterID = $lifter->ID;
        $result->LifterClassID = $lifterClass->ID;
        $result->LiftTypeID = $liftType->ID;
        $result->write();

        $records = $lifterClass->getCurrentClassResults();
        $this->assertEquals($records['Squat']['Weight'], 181);
        $this->assertEquals($records['Bench']['Weight'], 150);
        $this->assertEquals($records['Deadlift']['Weight'], 250);
        $this->assertEquals($records['Total']['Weight'], 560);

        $lifter = $this->objFromFixture(Lifter::class, 'lifterMaster1');
        $liftType = LiftType::get()->first();
        $lifterClass = $this->objFromFixture(LifterClass::class, 'testLifterClassMaster');
        $competition = Competition::get()->first();
        $result = new Result();
        $result->Age = $this->compResults[2]['Age'];
        $result->DateOfLift = $this->compResults[2]['DateOfLift'];
        $result->Gender = $this->compResults[2]['Gender'];
        $result->Squat = $this->compResults[2]['Squat'];
        $result->Bench = $this->compResults[2]['Bench'];
        $result->Deadlift = $this->compResults[2]['Deadlift'];
        $result->Total = $this->compResults[2]['Total'];
        $result->Active = 1;
        $result->CompetitionID = $competition->ID;
        $result->LifterID = $lifter->ID;
        $result->LifterClassID = $lifterClass->ID;
        $result->LiftTypeID = $liftType->ID;
        $result->write();

        $records = $lifterClass->getCurrentClassResults();
        $this->assertEquals($records['Squat']['Weight'], 200);
        $this->assertEquals($records['Bench']['Weight'], 165);
        $this->assertEquals($records['Deadlift']['Weight'], 250);
        $this->assertEquals($records['Total']['Weight'], 615);
    }
}
