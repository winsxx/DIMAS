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

        var_dump($query1);
        return view('query4')->with('result',$query4);
    }

    public function query1(){
        $admLevel = Input::get('adm_level');
        $locationName = Input::get('location_name');
        $disasterType = Input::get('disaster_type');
        $startDate = Input::get('start_date');
        $endDate = Input::get('end_date');

        $query1 = $this->disasterEvent($admLevel, $locationName, $disasterType, $startDate, $endDate);

        return $query1; #return view
    }

    public function query2(){
        $disasterEvent = Input::get('disaster_event');
        $disasterType = Input::get('disaster_type');

        $query2 = $this->disasterChanges($disasterEvent, $disasterType);

        return $query2;
    }

    public function query3(){
        $victimNik = Input::get('victim_nik');

        $query3 = $this->victimMovement($victimNik);

        return $query3;
    }

    public function query4(){
        $disasterEvent = Input::get('disaster_event');
        $disasterType = Input::get('disaster_type');
        $startDate = Input::get('start_date');
        $endDate = Input::get('end_date');
        
        $query4 = $this->villageAffected($disaterEvent, $disasterType, $startDate, $endDate);

        return $query4;
    }

    public function query5(){        
        $disasterEvent = Input::get('disaster_event');
        $disasterType = Input::get('disaster_type');
        $admLevel = Input::get('adm_level');
        $locationName = Input::get('location_name');
        $startDate = Input::get('start_date');
        $endDate = Input::get('end_date');

        $query5 = $this->victimList($disasterEvent, $disasterType, $admLevel, $locationName, $startDate, $endDate);

        return $query5;
    }

    public function query6(){
        $admLevel = Input::get('adm_level');
        $locationName = Input::get('location_name');

        $query6 = $this->refugeeCamp($admLevel, $locationName);

        return $query6;
    }

    public function query7(){
        $admLevel = Input::get('adm_level');
        $locationName = Input::get('location_name');

        $query7 = $this->medicalFacility($admLevel, $locationName);

        return $query7;
    }

    public function query8refugee(){
        $disasterEvent = Input::get('disaster_event');
        $disasterType = Input::get('disaster_type');
        $admLevel = Input::get('adm_level');
        $locationName = Input::get('location_name');
        $startDate = Input::get('start_date');
        $endDate = Input::get('end_date');
        $refugeeName = Input::get('refugee_name');
        $refugeeType = Input::get('refugee_type');

        $query8 = $this->numberOfVictimRefugeeCamp($disasterEvent, $disasterType, $admLevel, $locationName, 
             $startDate, $endDate, $refugeeName, $refugeeType);

        return $query8;
    }

    public function query8medical(){
        $disasterEvent = Input::get('disaster_event');
        $disasterType = Input::get('disaster_type');
        $admLevel = Input::get('adm_level');
        $locationName = Input::get('location_name');
        $startDate = Input::get('start_date');
        $endDate = Input::get('end_date');
        $medicalName = Input::get('medical_name');
        $medicalType = Input::get('medical_type');

        $query8 = $this->numberOfVictimMedicalFacility($disasterEvent, $disasterType, $admLevel, $locationName, 
             $startDate, $endDate, $medicalName, $medicalType);

        return $query8;
    }

    public function query8gender(){
        $disasterEvent = Input::get('disaster_event');
        $disasterType = Input::get('disaster_type');
        $admLevel = Input::get('adm_level');
        $locationName = Input::get('location_name');
        $startDate = Input::get('start_date');
        $endDate = Input::get('end_date');
        $gender = Input:: get('gender');

        $query8 = $this->numberOfVictimGender($disasterEvent, $disasterType, $admLevel, $locationName, 
             $startDate, $endDate, $gender);

        return $query8;
    }

    public function query8age(){
        $disasterEvent = Input::get('disaster_event');
        $disasterType = Input::get('disaster_type');
        $admLevel = Input::get('adm_level');
        $locationName = Input::get('location_name');
        $startDate = Input::get('start_date');
        $endDate = Input::get('end_date');
        $ageGroup = Input::get('age_group');

        $query = null;
        if($ageGroup == "baby"){
            $query = $this->numberOfVictimAgeGroup($disasterEvent, $disasterType, $admLevel, $locationName, 
                                                    $startDate, $endDate, 0, 0);
        } else if ($ageGroup == "toddler"){
            $query = $this->numberOfVictimAgeGroup($disasterEvent, $disasterType, $admLevel, $locationName, 
                                                    $startDate, $endDate, 1, 4);
        } else if ($ageGroup == "child"){
            $query = $this->numberOfVictimAgeGroup($disasterEvent, $disasterType, $admLevel, $locationName, 
                                                    $startDate, $endDate, 5, 12);
        } else if ($ageGroup == "teenager"){
            $query = $this->numberOfVictimAgeGroup($disasterEvent, $disasterType, $admLevel, $locationName, 
                                                    $startDate, $endDate, 13, 17);
        } else if ($ageGroup == "adult"){
            $query = $this->numberOfVictimAgeGroup($disasterEvent, $disasterType, $admLevel, $locationName, 
                                                    $startDate, $endDate, 18, 59);
        } else if ($ageGroup == "elderly"){
            $query = $this->numberOfVictimAgeGroup($disasterEvent, $disasterType, $admLevel, $locationName, 
                                                    $startDate, $endDate, 60, 999);
        }

        return $query;
    }

    public function query8status(){
        $disasterEvent = Input::get('disaster_event');
        $disasterType = Input::get('disaster_type');
        $admLevel = Input::get('adm_level');
        $locationName = Input::get('location_name');
        $startDate = Input::get('start_date');
        $endDate = Input::get('end_date');
        $status = Input::get('status');

        $query8 = $this->numberOfVictimStatus($disasterEvent, $disasterType, $admLevel, $locationName, 
             $startDate, $endDate, $status);

        return $query8;
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
            $query = $this->addCheckTimeIntersectionQuery($query, "disaster_coverage", $start_timestamp, $end_timestamp);
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
            $query = $this->addCheckTimeIntersectionQuery($query, "disaster_coverage", $start_timestamp, $end_timestamp);
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
            $query = $this->addCheckTimeIntersectionQuery($query, "disaster_coverage", $start_timestamp, $end_timestamp);
        }

        // var_dump($query);

        $villageList = DB::select(DB::raw($query));
        // var_dump($villageList);
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
            $query = $this->addCheckTimeIntersectionQuery($query, "victim_event", $start_timestamp, $end_timestamp);
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
            $query = $this->addCheckTimeIntersectionQuery($query, "victim_event", $start_timestamp, $end_timestamp);
        }

        # medical event
        if(isset($start_timestamp) && isset($end_timestamp)){
            $query = $this->addCheckTimeIntersectionQuery($query, "treated_at", $start_timestamp, $end_timestamp);
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
           $query = $this->addCheckTimeIntersectionQuery($query, "victim_event", $start_timestamp, $end_timestamp);
        }

        # refugee event
        if(isset($start_timestamp) && isset($end_timestamp)){
            $query = $this->addCheckTimeIntersectionQuery($query, "refugee_at", $start_timestamp, $end_timestamp);
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
            $query = $this->addCheckTimeIntersectionQuery($query, "victim_event", $start_timestamp, $end_timestamp);
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
            $query = $this->addCheckTimeIntersectionQuery($query, "victim_event", $start_timestamp, $end_timestamp);
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
           $query = $this->addCheckTimeIntersectionQuery($query, "victim_event", $start_timestamp, $end_timestamp);
        }

        # victim status
        if(isset($status)){
            $query = "{$query} AND victim_status.status = '{$status}'";
            $query = $this->addCheckTimeIntersectionQuery($query, "victim_status", $start_timestamp, $end_timestamp);
        }

        $number = DB::select(DB::raw($query));
        return $number;
    }

    private function addCheckTimeIntersectionQuery($prevQuery, $tableName, $start_timestamp, $end_timestamp){
        $newQuery = "{$prevQuery} AND (({$tableName}.end_time = '1970-01-01'::timestamp AND NOT('{$end_timestamp}'::timestamp <= {$tableName}.start_time)) OR ({$tableName}.end_time != '1970-01-01'::timestamp AND NOT('{$start_timestamp}'::timestamp >= {$tableName}.end_time OR '{$end_timestamp}'::timestamp <= {$tableName}.start_time)))";
        return $newQuery;
    }

}
