<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;

class DataController extends Controller
{
    public function testDatabase(){
    	$result = $this->victimMovement("1");
    	return dd($result);
    }

    private function victimMovement($victimId){
    	// $movement = DB::select( \DB::raw("SELECT ST_AsGeoJSON(ST_MakeLine(movement.geom)) FROM (SELECT victim_event.geom AS geom FROM victim, victim_event WHERE victim.nik = :victimid AND victim.nik = victim_event.nik ORDER BY victim_event.start_time) AS movement"), array('victimid' => $victimId));
    	$movement = DB::table('victim')
    					->join('victim_event', 'victim.nik', '=', 'victim_event.nik')
    					->where('victim.nik', $victimId)
    					->orderBy('victim_event.start_time', 'asc')->get();
    	$tes = DB::select(DB::raw('SELECT ST_AsGeoJSON(ST_MakeLine('.join(",",$movement).'))'))->get();
    	return $tes;
    }

}
