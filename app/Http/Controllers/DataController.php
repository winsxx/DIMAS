<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;

class DataController extends Controller
{
    public function testDatabase(){
    	$result = $this->victimMovement("111-56-8948");
        // $result2 = $this->disasterEvent("province", "riau", "banjir", '2012-04-01', '2012-05-01');
        // var_dump($result);
        // var_dump($result[0]);
        // var_dump($result[0]->st_asgeojson);
        // var_dump(json_decode($result[0]->st_asgeojson)->type);
    	// return $result;

        $query1 = $this->disasterEvent("province", "riau", "banjir", '2012-04-01', '2012-05-01');
        # Testing
        $query2 = $this->disasterChanges("D003", null);
        $query3 = $this->victimMovement("111-56-8948");
        $query4 = $this->villageAffected("D001", "banjir", "2015-07-23", "2015-07-24");
        $query5 = $this->victimList("D001", "banjir", "village", "Sawahan", "2015-07-23", "2015-07-24");
        $query6 = $this->refugeeCamp("village", "Sawahan");
        $query7 = $this->medicalFacility("village", "Sawahan");
        $query8medical = $this->numberOfVictimMedicalFacility("D001", "banjir", "village", "Sawahan", 
            "2015-07-23", "2015-07-24", "RS Kedasih", "Rumah Sakit");
        // $query8refugee = $this->numberOfVictimRefugeeCamp("D001", "banjir", "village", "Sawahan", 
        //     "2015-07-23", "2015-07-24", "Posko Bencana 1", "balai desa");
        $query8age = $this->numberOfVictimAgeGroup("D001", "banjir", "village", "Sawahan", 
            "2015-07-23", "2015-07-24", 20, 40);
        $query8gender = $this->numberOfVictimGender("D001", "banjir", "village", "Sawahan", 
            "2015-07-23", "2015-07-24","M");
        $query8status = $this->numberOfVictimStatus("D001", "banjir", "village", "Sawahan", 
            "2015-07-23", "2015-07-24", "missing");
    	// return $query8status;

        return view('query3')->with('result',$query3);
    }

    # Query no 3
    private function victimMovement($victimId){
        $movement = DB::select(DB::raw("SELECT ST_AsGeoJSON(ST_MakeLine(movement.geom)) FROM (SELECT victim_event.geom AS geom FROM victim, victim_event WHERE victim.nik = :victimid AND victim.nik = victim_event.nik ORDER BY victim_event.start_time) AS movement"), array('victimid' => $victimId));
        return $movement;
    }

    # Query no 1
    private function disasterEvent($admLevel, $name, $type, $start_timestamp, $end_timestamp){
        $query = "SELECT DISTINCT disaster_coverage.disaster_id FROM disaster_coverage, village WHERE ST_Intersects(disaster_coverage.geom, village.geom)";

        # administration level and name
        if(isset($admLevel) && isset($name)){
            if($admLevel == 'village'){
                $query = "{$query} AND village.village_name='{$name}'";
            } else if ($admLevel == 'district'){
                $query = "{$query} AND village.district='{$name}'";
            } else if ($admLevel == 'subdistrict'){
                $query = "{$query} AND village.subdistrict='{$name}'";
            } else if ($admLevel == 'province'){
                $query = "{$query} AND village.province='{$name}'";
            }
        }

        # type
        if(isset($type)){
            $query = "{$query} AND disaster_coverage.disaster_type='{$type}'";
        }

        # time
        if(isset($start_timestamp) && isset($end_timestamp)){
            $query = "{$query} AND ((NOT (disaster_coverage.end_time = '1970-01-01'::timestamp AND '{$start_timestamp}'::timestamp < disaster_coverage.start_time AND '{$end_timestamp}'::timestamp < disaster_coverage.start_time)) OR (disaster_coverage.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp > disaster_coverage.end_time OR '{$end_timestamp}'::timestamp < disaster_coverage.start_time)))";
        }

        $event = DB::select(DB::raw($query));
        return $event;
    }

    # Query no 2
    private function disasterChanges($disaster, $type){
        $query = "SELECT start_time, end_time FROM disaster_coverage WHERE disaster_coverage.disaster_id='{$disaster}'";

        if(isset($type)){
            $query = "{$query} AND disaster_coverage.disaster_type='{$type}'";
        }

        $intimes = DB::select(DB::raw($query));

        $timeArray = array();
        foreach ($intimes as $row) {
            array_push($timeArray, $row->start_time);
            array_push($timeArray, $row->end_time);
        }
        array_multisort($timeArray);
        $timeUniqueArray = array();
        $tSize = sizeof($timeArray);
        if($tSize>0){
            array_push($timeUniqueArray, $timeArray[0]);
            for($i=1; $i<$tSize; $i++){
                if(end($timeUniqueArray) != $timeArray[$i]){
                    array_push($timeUniqueArray, $timeArray[$i]);
                }
            }
        }

        $result = array();
        for($i=1; $i<sizeof($timeArray); $i++){
            $query = "SELECT disaster_id, ST_AsGeoJSON(ST_Collect(geom)) FROM disaster_coverage WHERE disaster_id = '{$disaster}'";
            if(isset($type)){
                $query = "{$query} AND disaster_type='{$type}'";
            }
            $start_timestamp = $timeArray[$i-1];
            $end_timestamp = $timeArray[$i];
            $query = "{$query} AND ((NOT (disaster_coverage.end_time = '1970-01-01'::timestamp AND '{$start_timestamp}'::timestamp < disaster_coverage.start_time AND '{$end_timestamp}'::timestamp < disaster_coverage.start_time)) OR (disaster_coverage.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp > disaster_coverage.end_time OR '{$end_timestamp}'::timestamp < disaster_coverage.start_time)))";
            $query = "{$query} GROUP BY disaster_id";
            $region = DB::select(DB::raw($query));
            array_push($result, array("start_time" => $start_timestamp, 
                "end_time" => $end_timestamp ,"region" => $region));
        }
        
        return $result;
    }

    # Query no 4
    private function villageAffected($disaster, $disasterType, $start_timestamp, $end_timestamp){
        $query = "SELECT DISTINCT village.village_id FROM disaster_coverage, village WHERE ST_Intersects(disaster_coverage.geom, village.geom)";

        # disaster event
        if(isset($disaster)){
            $query = "{$query} AND disaster_coverage.disaster_id = '{$disaster}'";
        }

        # disaster type
        if(isset($disasterType)){
            $query = "{$query} AND disaster_coverage.disaster_type = '{$disasterType}'";
        }

        # time
        if(isset($start_timestamp) && isset($end_timestamp)){
            $query = "{$query} AND ((NOT (disaster_coverage.end_time = '1970-01-01'::timestamp AND '{$start_timestamp}'::timestamp < disaster_coverage.start_time AND '{$end_timestamp}'::timestamp < disaster_coverage.start_time)) OR (disaster_coverage.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp > disaster_coverage.end_time OR '{$end_timestamp}'::timestamp < disaster_coverage.start_time)))";
        }

        $villageList = DB::select(DB::raw($query));
        return $villageList;
    }

    # Query no 5
    private function victimList($disaster, $disasterType, $admLevel, $locationName, $start_timestamp, $end_timestamp){
        $query = "SELECT DISTINCT victim.nik FROM victim, victim_of, victim_event, disaster_event, disaster_type, village WHERE victim.nik = victim_of.nik AND victim.nik = victim_event.nik AND victim_of.id = disaster_event.event_id AND disaster_event.event_id = disaster_type.disaster_id AND victim.origin = village.village_id";

        # disaster event
        if(isset($disaster)){
            $query = "{$query} AND disaster_event.event_id='{$disaster}'";
        }

        # disaster type
        if(isset($disasterType)){
            $query = "{$query} AND disaster_type.disaster_type='{$disasterType}'";
        }

        # administration level and name
        if(isset($admLevel) && isset($locationName)){
            if($admLevel == 'village'){
                $query = "{$query} AND village.village_name='{$locationName}'";
            } else if ($admLevel == 'district'){
                $query = "{$query} AND village.district='{$locationName}'";
            } else if ($admLevel == 'subdistrict'){
                $query = "{$query} AND village.subdistrict='{$locationName}'";
            } else if ($admLevel == 'province'){
                $query = "{$query} AND village.province='{$locationName}'";
            }
        }

        if(isset($start_timestamp) && isset($end_timestamp)){
            $query = "{$query} AND ((NOT (victim_event.end_time = '1970-01-01'::timestamp AND '{$start_timestamp}'::timestamp < victim_event.start_time AND '{$end_timestamp}'::timestamp < victim_event.start_time)) OR (victim_event.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp > victim_event.end_time OR '{$end_timestamp}'::timestamp < victim_event.start_time)))";
        }

        $victimList = DB::select(DB::raw($query));
        return $victimList;
    }

    # Query 6
    private function refugeeCamp($admLevel, $name){
        $query = "SELECT refuge_camp.camp_name FROM village, refuge_camp WHERE refuge_camp.location = village.village_id";

        # administration level and name
        if(isset($admLevel) && isset($name)){
            if($admLevel == 'village'){
                $query = "{$query} AND village.village_name='{$name}'";
            } else if ($admLevel == 'district'){
                $query = "{$query} AND village.district='{$name}'";
            } else if ($admLevel == 'subdistrict'){
                $query = "{$query} AND village.subdistrict='{$name}'";
            } else if ($admLevel == 'province'){
                $query = "{$query} AND village.province='{$name}'";
            }
        }

        $campList = DB::select(DB::raw($query));
        return $campList;
    }

    # Query 7
    private function medicalFacility($admLevel, $name){
        $query = "SELECT med_facility.facility_name FROM village, med_facility WHERE med_facility.location = village.village_id";

        # administration level and name
        if(isset($admLevel) && isset($name)){
            if($admLevel == 'village'){
                $query = "{$query} AND village.village_name='{$name}'";
            } else if ($admLevel == 'district'){
                $query = "{$query} AND village.district='{$name}'";
            } else if ($admLevel == 'subdistrict'){
                $query = "{$query} AND village.subdistrict='{$name}'";
            } else if ($admLevel == 'province'){
                $query = "{$query} AND village.province='{$name}'";
            }
        }

        $facilityList = DB::select(DB::raw($query));
        return $facilityList;
    }

    # Query 8 
    private function numberOfVictimMedicalFacility($disaster, $disasterType, $admLevel, 
        $locationName, $start_timestamp, $end_timestamp, $medFacilityName, $medFacilityType){

        $query = "SELECT COUNT(DISTINCT victim.nik) FROM victim, victim_of, victim_event, disaster_event, disaster_type, treated_at, med_facility, village WHERE victim.nik = victim_of.nik AND victim_of.id = disaster_event.event_id AND victim_event.nik = victim.nik AND disaster_type.disaster_id = disaster_event.event_id AND treated_at.nik = victim.nik AND med_facility.facility_id = treated_at.facility_id AND ST_Contains(village.geom, victim_event.geom)";

        # disaster event
        if(isset($disaster)){
            $query = "{$query} AND disaster_event.event_id='{$disaster}'";
        }

        # disaster type
        if(isset($disasterType)){
            $query = "{$query} AND disaster_type.disaster_type='{$disasterType}'";
        }

        # disaster type
        if(isset($medFacilityName)){
            $query = "{$query} AND med_facility.facility_name = '{$medFacilityName}'";
        }

        # facility name
        if(isset($medFacilityType)){
            $query = "{$query} AND med_facility.facility_type = '{$medFacilityType}'";
        }

        # administration level and name
        if(isset($admLevel) && isset($locationName)){
            if($admLevel == 'village'){
                $query = "{$query} AND village.village_name='{$locationName}'";
            } else if ($admLevel == 'district'){
                $query = "{$query} AND village.district='{$locationName}'";
            } else if ($admLevel == 'subdistrict'){
                $query = "{$query} AND village.subdistrict='{$locationName}'";
            } else if ($admLevel == 'province'){
                $query = "{$query} AND village.province='{$locationName}'";
            }
        }

        # victim event
        if(isset($start_timestamp) && isset($end_timestamp)){
            $query = "{$query} AND ((NOT (victim_event.end_time = '1970-01-01'::timestamp AND '{$start_timestamp}'::timestamp < victim_event.start_time AND '{$end_timestamp}'::timestamp < victim_event.start_time)) OR (victim_event.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp > victim_event.end_time OR '{$end_timestamp}'::timestamp < victim_event.start_time)))";
        }

        # medical event
        if(isset($start_timestamp) && isset($end_timestamp)){
            $query = "{$query} AND ((NOT (treated_at.end_time = '1970-01-01'::timestamp AND '{$start_timestamp}'::timestamp < treated_at.start_time AND '{$end_timestamp}'::timestamp < treated_at.start_time)) OR (treated_at.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp > treated_at.end_time OR '{$end_timestamp}'::timestamp < treated_at.start_time)))";
        }

        $number = DB::select(DB::raw($query));
        return $number;
    }

    # Query 8
    private function numberOfVictimRefugeeCamp($disaster, $disasterType, $admLevel, 
        $locationName, $start_timestamp, $end_timestamp, $campName, $campType){

        $query = "SELECT COUNT(DISTINCT victim.nik) FROM victim, victim_of, victim_event, disaster_event, disaster_type, refugee_at, refuge_camp, village WHERE victim.nik = victim_of.nik AND victim_of.id = disaster_event.event_id AND victim_event.nik = victim.nik AND disaster_type.disaster_id = disaster_event.event_id AND refugee_at.nik = victim.nik AND refugee_at.camp_id = refuge_camp.camp_id AND ST_Contains(village.geom, victim_event.geom)";

        # disaster event
        if(isset($disaster)){
            $query = "{$query} AND disaster_event.event_id='{$disaster}'";
        }

        # disaster type
        if(isset($disasterType)){
            $query = "{$query} AND disaster_type.disaster_type='{$disasterType}'";
        }

        # refugee name
        if(isset($campName)){
            $query = "{$query} AND refuge_camp.camp_name = '{$campName}'";
        }

        # refugee type
        if(isset($campType)){
            $query = "{$query} AND refuge_camp.building_type = '{$campType}'";
        }

        # administration level and name
        if(isset($admLevel) && isset($locationName)){
            if($admLevel == 'village'){
                $query = "{$query} AND village.village_name='{$locationName}'";
            } else if ($admLevel == 'district'){
                $query = "{$query} AND village.district='{$locationName}'";
            } else if ($admLevel == 'subdistrict'){
                $query = "{$query} AND village.subdistrict='{$locationName}'";
            } else if ($admLevel == 'province'){
                $query = "{$query} AND village.province='{$locationName}'";
            }
        }

        # victim event
        if(isset($start_timestamp) && isset($end_timestamp)){
            $query = "{$query} AND ((NOT (victim_event.end_time = '1970-01-01'::timestamp AND '{$start_timestamp}'::timestamp < victim_event.start_time AND '{$end_timestamp}'::timestamp < victim_event.start_time)) OR (victim_event.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp > victim_event.end_time OR '{$end_timestamp}'::timestamp < victim_event.start_time)))";
        }

        # refugee event
        if(isset($start_timestamp) && isset($end_timestamp)){
            $query = "{$query} AND ((NOT (refugee_at.end_time = '1970-01-01'::timestamp AND '{$start_timestamp}'::timestamp < refugee_at.start_time AND '{$end_timestamp}'::timestamp < refugee_at.start_time)) OR (refugee_at.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp > refugee_at.end_time OR '{$end_timestamp}'::timestamp < refugee_at.start_time)))";
        }

        $number = DB::select(DB::raw($query));
        return $number;
    }

    # Query 8
    private function numberOfVictimAgeGroup($disaster, $disasterType, $admLevel, 
        $locationName, $start_timestamp, $end_timestamp, $minAge, $maxAge){

        $query = "SELECT COUNT(DISTINCT victim.nik) FROM victim, victim_of, victim_event, disaster_event, disaster_type, village WHERE victim.nik = victim_of.nik AND victim_of.id = disaster_event.event_id AND victim_event.nik = victim.nik AND disaster_type.disaster_id = disaster_event.event_id AND ST_Contains(village.geom, victim_event.geom)";

        # disaster event
        if(isset($disaster)){
            $query = "{$query} AND disaster_event.event_id='{$disaster}'";
        }

        # disaster type
        if(isset($disasterType)){
            $query = "{$query} AND disaster_type.disaster_type='{$disasterType}'";
        }

        # administration level and name
        if(isset($admLevel) && isset($locationName)){
            if($admLevel == 'village'){
                $query = "{$query} AND village.village_name='{$locationName}'";
            } else if ($admLevel == 'district'){
                $query = "{$query} AND village.district='{$locationName}'";
            } else if ($admLevel == 'subdistrict'){
                $query = "{$query} AND village.subdistrict='{$locationName}'";
            } else if ($admLevel == 'province'){
                $query = "{$query} AND village.province='{$locationName}'";
            }
        }

        # victim event
        if(isset($start_timestamp) && isset($end_timestamp)){
            $query = "{$query} AND ((NOT (victim_event.end_time = '1970-01-01'::timestamp AND '{$start_timestamp}'::timestamp < victim_event.start_time AND '{$end_timestamp}'::timestamp < victim_event.start_time)) OR (victim_event.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp > victim_event.end_time OR '{$end_timestamp}'::timestamp < victim_event.start_time)))";
        }

        # age
        if(isset($minAge) && isset($maxAge)){
            $query = "{$query} AND extract(year from AGE(now(), to_timestamp(victim.dob, 'MM/DD/YYYY'))) > {$minAge} AND extract(year from AGE(now(), to_timestamp(victim.dob, 'MM/DD/YYYY'))) < {$maxAge}";
        }

        $number = DB::select(DB::raw($query));
        return $number;
    }

    # Query 8
    private function numberOfVictimGender($disaster, $disasterType, $admLevel, 
        $locationName, $start_timestamp, $end_timestamp, $gender){

        $query = "SELECT COUNT(DISTINCT victim.nik) FROM victim, victim_of, victim_event, disaster_event, disaster_type, village WHERE victim.nik = victim_of.nik AND victim_of.id = disaster_event.event_id AND victim_event.nik = victim.nik AND disaster_type.disaster_id = disaster_event.event_id AND ST_Contains(village.geom, victim_event.geom)";

        # disaster event
        if(isset($disaster)){
            $query = "{$query} AND disaster_event.event_id='{$disaster}'";
        }

        # disaster type
        if(isset($disasterType)){
            $query = "{$query} AND disaster_type.disaster_type='{$disasterType}'";
        }

        # administration level and name
        if(isset($admLevel) && isset($locationName)){
            if($admLevel == 'village'){
                $query = "{$query} AND village.village_name='{$locationName}'";
            } else if ($admLevel == 'district'){
                $query = "{$query} AND village.district='{$locationName}'";
            } else if ($admLevel == 'subdistrict'){
                $query = "{$query} AND village.subdistrict='{$locationName}'";
            } else if ($admLevel == 'province'){
                $query = "{$query} AND village.province='{$locationName}'";
            }
        }

        # victim event
        if(isset($start_timestamp) && isset($end_timestamp)){
            $query = "{$query} AND ((NOT (victim_event.end_time = '1970-01-01'::timestamp AND '{$start_timestamp}'::timestamp < victim_event.start_time AND '{$end_timestamp}'::timestamp < victim_event.start_time)) OR (victim_event.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp > victim_event.end_time OR '{$end_timestamp}'::timestamp < victim_event.start_time)))";
        }

        # gender
        if(isset($gender)){
            $query = "{$query} AND victim.gender = '{$gender}'";
        }

        $number = DB::select(DB::raw($query));
        return $number;
    }

    # Query 8
    private function numberOfVictimStatus($disaster, $disasterType, $admLevel, 
        $locationName, $start_timestamp, $end_timestamp, $status){

        $query = "SELECT COUNT(DISTINCT victim.nik) FROM victim, victim_of, victim_event, disaster_event, disaster_type, village, victim_status WHERE victim.nik = victim_of.nik  AND victim_of.id = disaster_event.event_id  AND victim_event.nik = victim.nik AND victim_status.nik = victim.nik AND disaster_type.disaster_id = disaster_event.event_id  AND ST_Contains(village.geom, victim_event.geom)";

        # disaster event
        if(isset($disaster)){
            $query = "{$query} AND disaster_event.event_id='{$disaster}'";
        }

        # disaster type
        if(isset($disasterType)){
            $query = "{$query} AND disaster_type.disaster_type='{$disasterType}'";
        }

        # administration level and name
        if(isset($admLevel) && isset($locationName)){
            if($admLevel == 'village'){
                $query = "{$query} AND village.village_name='{$locationName}'";
            } else if ($admLevel == 'district'){
                $query = "{$query} AND village.district='{$locationName}'";
            } else if ($admLevel == 'subdistrict'){
                $query = "{$query} AND village.subdistrict='{$locationName}'";
            } else if ($admLevel == 'province'){
                $query = "{$query} AND village.province='{$locationName}'";
            }
        }

        # victim event
        if(isset($start_timestamp) && isset($end_timestamp)){
            $query = "{$query} AND ((NOT (victim_event.end_time = '1970-01-01'::timestamp AND '{$start_timestamp}'::timestamp < victim_event.start_time AND '{$end_timestamp}'::timestamp < victim_event.start_time)) OR (victim_event.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp > victim_event.end_time OR '{$end_timestamp}'::timestamp < victim_event.start_time)))";
        }

        # victim status
        if(isset($status)){
            $query = "{$query} AND victim_status.status = '{$status}'";
            $query = "{$query} AND ((NOT (victim_status.end_time = '1970-01-01'::timestamp AND '{$start_timestamp}'::timestamp < victim_status.start_time AND '{$end_timestamp}'::timestamp < victim_status.start_time)) OR (victim_status.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp > victim_status.end_time OR '{$end_timestamp}'::timestamp < victim_status.start_time)))";
        }

        $number = DB::select(DB::raw($query));
        return $number;
    }
}
