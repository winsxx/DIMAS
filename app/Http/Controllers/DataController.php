<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;

class DataController extends Controller
{
    public function testDatabase(){
        $query1 = $this->disasterEvent("province", "riau", "banjir", '2012-04-01', '2012-05-01');
        $query3 = $this->victimMovement("111-56-8948");
        $query4 = $this->villageAffected("D001", "banjir", "2015-07-23", "2015-07-24");
        $query5 = $this->victimList("D001", "banjir", "village", "Sawahan", "2015-07-23", "2015-07-24");
        $query6 = $this->refugeeCamp("village", "Sawahan");
        $query7 = $this->medicalFacility("village", "Sawahan");
    	return $query4;
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

    # Query no 3
    private function victimMovement($victimId){
        $movement = DB::select(DB::raw("SELECT ST_AsGeoJSON(ST_MakeLine(movement.geom)) FROM (SELECT victim_event.geom AS geom FROM victim, victim_event WHERE victim.nik = :victimid AND victim.nik = victim_event.nik ORDER BY victim_event.start_time) AS movement"), array('victimid' => $victimId));
        return $movement;
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


}
