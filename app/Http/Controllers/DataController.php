<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;

class DataController extends Controller
{
    public function testDatabase(){
    	$result = $this->victimMovement("111-56-8948");
        $result2 = $this->disasterEvent("province", "riau", "banjir", '2012-04-01', '2012-05-01');
    	return $result2;
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

}
