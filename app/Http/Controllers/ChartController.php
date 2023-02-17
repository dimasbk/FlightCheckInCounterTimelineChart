<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlightModel;
use App\Models\CheckinDeskModel;
use App\Models\AirlineModel;
use App\Models\AirportCodeModel;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function domestik()
    {
        return view('chartdom');
    }

    public function internasional()
    {
        return view('chartint');
    }

    public function addDataDomestik()
    {
        $airport = AirportCodeModel::where('type', 'Domestik')->get();
        $airline = AirlineModel::where('type', 'Domestik')->get();
        $checkinDesk = CheckinDeskModel::where('type', 'Domestik')->get();
        $data = compact(['airport'], ['airline'], ['checkinDesk']);
        //dd($checkinDesk);
        return view('addData', $data);
    }

    public function addDataInternasional()
    {
        $airport = AirportCodeModel::where('type', 'Internasional')->get();
        $airline = AirlineModel::where('type', 'Internasional')->get();
        $checkinDesk = CheckinDeskModel::where('type', 'Internasional')->get();
        $data = compact(['airport'], ['airline'], ['checkinDesk']);
        //dd($data);
        return view('addData', $data);
    }

    public function insert(Request $request)
    {

        $insert = FlightModel::create([
            'flight_number' => $request->flight_number,
            'id_destination' => $request->airport,
            'airplane_type' => $request->airplane_type,
            'id_airline' => $request->airline,
            'schedule_time' => $request->schedule_time,
            'id_checkin_desk' => $request->checkin,
            'gate' => $request->gate,
            'pax' => $request->pax,
            'cic' => $request->cic,
            'flightType' => $request->flight_type
        ]);

        $type = $request->flight_type;

        if ($type == "Internasional") {
            return redirect('/flight/internasional');
        } else {
            return redirect('/flight/domestik');
        }
    }


    public function counter()
    {
        $counter = CheckinDeskModel::get();
        return $counter;
    }

    public function modal($id)
    {
        $flightData = FlightModel::join('tb_airline', 'tb_schedule.id_airline', '=', 'tb_airline.id_airline')
            ->join('tb_airport', 'tb_schedule.id_destination', '=', 'tb_airport.id_aiport')
            ->join('tb_checkin_desk', 'tb_schedule.id_checkin_desk', '=', 'tb_checkin_desk.id')
            ->where("flight_number", $id)
            ->get()
            ->toArray();

        return $flightData;
    }
    public function search(Request $request)
    {
        $from = date('Y-m-d H:i:s', $request->from);

        $to = date('Y-m-d H:i:s', $request->to);
        $data = FlightModel::select('id_schedule', 'flight_number', 'id_checkin_desk')
            ->where('flight_number', $request->param)->where('flightType', $request->type)->whereBetween('schedule_time', [$from, $to])->get();

        return $data;
    }

    public function flightDataDomestik(Request $request)
    {


        $from = date('Y-m-d H:i:s', $request->from);

        $to = date('Y-m-d H:i:s', $request->to);
        $flightData = FlightModel::join('tb_airline', 'tb_schedule.id_airline', '=', 'tb_airline.id_airline')
            ->join('tb_airport', 'tb_schedule.id_destination', '=', 'tb_airport.id_aiport')
            ->whereBetween('schedule_time', [$from, $to])
            ->where("flightType", 'Domestik')
            ->get()
            ->toArray();
        //dd($to);    
        $counter = CheckinDeskModel::where('type', 'Domestik')->get();
        $data = compact(['flightData', ['counter']]);
        //dd($data);
        return $data;

    }
    public function flightDataInternasional(Request $request)
    {
        /*
        $startTime = new \DateTime('midnight');
        $from = $startTime->format('Y-m-d H:i:s');
        $endTime = new \DateTime('midnight');
        $to = $endTime->setTime(23, 59, 00)->format('Y-m-d H:i:s');
        */

        $from = date('Y-m-d H:i:s', $request->from);

        $to = date('Y-m-d H:i:s', $request->to);
        $flightData = FlightModel::join('tb_airline', 'tb_schedule.id_airline', '=', 'tb_airline.id_airline')
            ->join('tb_airport', 'tb_schedule.id_destination', '=', 'tb_airport.id_aiport')
            ->join('tb_checkin_desk', 'tb_schedule.id_checkin_desk', '=', 'tb_checkin_desk.id')
            ->orderByRaw('CAST(checkin_desk AS UNSIGNED)=0, CAST(checkin_desk AS UNSIGNED), LEFT(checkin_desk,1),CAST(MID(checkin_desk,2) AS UNSIGNED)')
            ->whereBetween('schedule_time', [$from, $to])
            ->where("flightType", 'Internasional')
            ->get()
            ->toArray();
        $counter = CheckinDeskModel::where('type', 'Internasional')->get();
        $data = compact(['flightData', ['counter']]);
        return $data;
    }
}