<?php

namespace App\Http\Controllers;

use App\Exports\ExportFlightData;
use App\Exports\FlightExportDomestic;
use App\Models\ArrivalFlightModel;
use App\Models\BeltModel;
use Illuminate\Http\Request;
use App\Models\DepartureFlightModel;
use App\Models\CheckinDeskModel;
use App\Models\AirlineModel;
use App\Models\AirportCodeModel;
use App\Models\GateModel;
use App\Imports\ImportFlightData;
use Maatwebsite\Excel\Facades\Excel;
use \PDF;

class ChartController extends Controller
{
    public function departureDomestik()
    {
        return view('chartdomDeparture');
    }

    public function departureInternasional()
    {
        return view('chartintDeparture');
    }

    public function gateDomestik()
    {
        return view('chartdomGate');
    }

    public function gateInternasional()
    {
        return view('chartintGate');
    }

    public function arrivalDomestik()
    {
        return view('chartdomArri');
    }

    public function arrivalInternasional()
    {
        return view('chartintArri');
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

        $insert = DepartureFlightModel::create([
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

    public function desk(Request $request)
    {
        $first = $request->first;
        $last = $request->last;

        $firstDesk = CheckinDeskModel::where('id', $first)->value('checkin_desk');
        $lastDesk = CheckinDeskModel::where('id', $last)->value('checkin_desk');

        $data = compact(['firstDesk'], ['lastDesk']);

        return $data;
    }
    public function counter()
    {
        $counter = CheckinDeskModel::get();
        return $counter;
    }

    public function modal(Request $request)
    {
        $flightData = DepartureFlightModel::join('tb_airline', 'tb_departure.id_airline', '=', 'tb_airline.id_airline')
            ->join('tb_airport', 'tb_departure.id_destination', '=', 'tb_airport.id_aiport')
            ->join('tb_checkin_desk', 'tb_departure.id_checkin_desk', '=', 'tb_checkin_desk.id')
            ->where("id_departure", $request->id)
            ->get()
            ->toArray();

        return $flightData;
    }

    public function modalArrival(Request $request)
    {
        $flightData = ArrivalFlightModel::join('tb_airline', 'tb_arrival.id_airline', '=', 'tb_airline.id_airline')
            ->join('tb_airport', 'tb_arrival.id_origin', '=', 'tb_airport.id_aiport')
            ->join('tb_belt', 'tb_arrival.belt', '=', 'tb_belt.id')
            ->where("id_arrival", $request->id)
            ->get()
            ->toArray();

        return $flightData;
    }
    public function search(Request $request)
    {
        $from = date('Y-m-d H:i:s', $request->from);

        $to = date('Y-m-d H:i:s', $request->to);
        $data = DepartureFlightModel::select('id_departure', 'flight_number', 'id_checkin_desk')
            ->where('flight_number', $request->param)->where('flightType', $request->type)->whereBetween('schedule_time', [$from, $to])->get();

        return $data;
    }

    public function searchArrival(Request $request)
    {
        $from = date('Y-m-d H:i:s', $request->from);

        $to = date('Y-m-d H:i:s', $request->to);
        $data = ArrivalFlightModel::where('flight_number', $request->param)->where('flightType', $request->type)->whereBetween('schedule_time', [$from, $to])->value('id_arrival');

        return $data;
    }

    public function arrivalDataDomestik(Request $request)
    {
        $from = date('Y-m-d H:i:s', $request->from);

        $to = date('Y-m-d H:i:s', $request->to);

        $flightData = ArrivalFlightModel::join('tb_airline', 'tb_arrival.id_airline', '=', 'tb_airline.id_airline')
            ->join('tb_airport', 'tb_arrival.id_origin', '=', 'tb_airport.id_aiport')
            ->whereBetween('schedule_time', [$from, $to])
            ->where("flightType", 'Domestik')
            ->get()
            ->toArray();

        $belt = BeltModel::where('type', 'Domestik')->get();
        $data = compact(['flightData', ['belt']]);
        return $data;
    }

    public function arrivalDataInternasional(Request $request)
    {
        $from = date('Y-m-d H:i:s', $request->from);

        $to = date('Y-m-d H:i:s', $request->to);

        $flightData = ArrivalFlightModel::join('tb_airline', 'tb_arrival.id_airline', '=', 'tb_airline.id_airline')
            ->join('tb_airport', 'tb_arrival.id_origin', '=', 'tb_airport.id_aiport')
            ->whereBetween('schedule_time', [$from, $to])
            ->where("flightType", 'Internasional')
            ->get()
            ->toArray();

        $belt = BeltModel::where('type', 'Internasional')->get();
        $data = compact(['flightData', ['belt']]);
        return $data;
    }

    public function gateDataDomestik(Request $request)
    {


        $from = date('Y-m-d H:i:s', $request->from);

        $to = date('Y-m-d H:i:s', $request->to);
        $flightData = DepartureFlightModel::join('tb_airline', 'tb_departure.id_airline', '=', 'tb_airline.id_airline')
            ->join('tb_airport', 'tb_departure.id_destination', '=', 'tb_airport.id_aiport')
            ->whereBetween('schedule_time', [$from, $to])
            ->where("flightType", 'Domestik')
            ->get()
            ->toArray();
        //dd($to);    
        $gate = GateModel::where('type', 'Domestik')->get();
        $data = compact(['flightData', ['gate']]);
        //dd($data);
        return $data;

    }
    public function gateDataInternasional(Request $request)
    {
        /*
        $startTime = new \DateTime('midnight');
        $from = $startTime->format('Y-m-d H:i:s');
        $endTime = new \DateTime('midnight');
        $to = $endTime->setTime(23, 59, 00)->format('Y-m-d H:i:s');
        */

        $from = date('Y-m-d H:i:s', $request->from);

        $to = date('Y-m-d H:i:s', $request->to);
        $flightData = DepartureFlightModel::join('tb_airline', 'tb_departure.id_airline', '=', 'tb_airline.id_airline')
            ->join('tb_airport', 'tb_departure.id_destination', '=', 'tb_airport.id_aiport')
            ->join('tb_checkin_desk', 'tb_departure.id_checkin_desk', '=', 'tb_checkin_desk.id')
            //->orderByRaw('CAST(checkin_desk AS UNSIGNED)=0, CAST(checkin_desk AS UNSIGNED), LEFT(checkin_desk,1),CAST(MID(checkin_desk,2) AS UNSIGNED)')
            ->whereBetween('schedule_time', [$from, $to])
            ->where("flightType", 'Internasional')
            ->get()
            ->toArray();
        $gate = GateModel::where('type', 'Internasional')->get();
        $data = compact(['flightData', ['gate']]);
        return $data;
    }

    public function flightDataDomestik(Request $request)
    {


        $from = date('Y-m-d H:i:s', $request->from);

        $to = date('Y-m-d H:i:s', $request->to);
        $flightData = DepartureFlightModel::join('tb_airline', 'tb_departure.id_airline', '=', 'tb_airline.id_airline')
            ->join('tb_airport', 'tb_departure.id_destination', '=', 'tb_airport.id_aiport')
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
        $flightData = DepartureFlightModel::join('tb_airline', 'tb_departure.id_airline', '=', 'tb_airline.id_airline')
            ->join('tb_airport', 'tb_departure.id_destination', '=', 'tb_airport.id_aiport')
            ->join('tb_checkin_desk', 'tb_departure.id_checkin_desk', '=', 'tb_checkin_desk.id')
            //->orderByRaw('CAST(checkin_desk AS UNSIGNED)=0, CAST(checkin_desk AS UNSIGNED), LEFT(checkin_desk,1),CAST(MID(checkin_desk,2) AS UNSIGNED)')
            ->whereBetween('schedule_time', [$from, $to])
            ->where("flightType", 'Internasional')
            ->get()
            ->toArray();
        $counter = CheckinDeskModel::where('type', 'Internasional')->get();
        $data = compact(['flightData', ['counter']]);
        return $data;
    }

    public function import(Request $request)
    {

        Excel::import(new ImportFlightData, $request->file('file'));
        return back();
        ;
    }

    public function export(Request $request)
    {
        $departureDomestik = DepartureFlightModel::whereDate('schedule_time', $request->exportDate)->where('flightType', 'Domestik')
            ->join('tb_airport', 'tb_departure.id_destination', '=', 'tb_airport.id_aiport')
            ->orderBy('schedule_time', 'ASC')
            ->get()->toArray();

        $arrayDomestikDeparture = [];
        foreach ($departureDomestik as $hasil) {
            $flightNumber = $hasil["flight_number"];
            $destination = $hasil["airport_code"];
            $airplaneType = $hasil["airplane_type"];
            $scheduleTime = $hasil["schedule_time"];
            $checkinDesk = $hasil["id_checkin_desk"];
            $first = substr($checkinDesk, 0, 2);
            $last = substr($checkinDesk, -2);
            $firstDesk = CheckinDeskModel::where('id', $first)->value('checkin_desk');
            $lastDesk = CheckinDeskModel::where('id', $last)->value('checkin_desk');
            $desk = $firstDesk . "-" . $lastDesk;
            $gate = $hasil["gate"];
            $pax = $hasil["pax"];
            $cic = $hasil["cic"];

            array_push($arrayDomestikDeparture, [$flightNumber, $destination, $airplaneType, $scheduleTime, $desk, $gate, $pax, $cic]);
        }

        $departureInternasional = DepartureFlightModel::whereDate('schedule_time', $request->exportDate)->where('flightType', 'Internasional')
            ->join('tb_airport', 'tb_departure.id_destination', '=', 'tb_airport.id_aiport')
            ->orderBy('schedule_time', 'ASC')
            ->get()->toArray();

        $arrayInternasionalDeparture = [];
        foreach ($departureInternasional as $hasil) {
            $flightNumber = $hasil["flight_number"];
            $destination = $hasil["airport_code"];
            $airplaneType = $hasil["airplane_type"];
            $scheduleTime = $hasil["schedule_time"];
            $checkinDesk = $hasil["id_checkin_desk"];
            $deskArray = explode(",", $checkinDesk);
            $firstDesk = CheckinDeskModel::where('id', reset($deskArray))->value('checkin_desk');
            $lastDesk = CheckinDeskModel::where('id', end($deskArray))->value('checkin_desk');
            $desk = $firstDesk . "-" . $lastDesk;
            $gate = $hasil["gate"];
            $pax = $hasil["pax"];
            $cic = $hasil["cic"];

            array_push($arrayInternasionalDeparture, [$flightNumber, $destination, $airplaneType, $scheduleTime, $desk, $gate, $pax, $cic]);
        }

        $arrivalDomestik = ArrivalFlightModel::whereDate('schedule_time', $request->exportDate)->where('flightType', 'Domestik')
            ->join('tb_airport', 'tb_arrival.id_origin', '=', 'tb_airport.id_aiport')
            ->join('tb_belt', 'tb_arrival.belt', '=', 'tb_belt.id')
            ->orderBy('schedule_time', 'ASC')
            ->get()->toArray();

        $arrayDomestikArrival = [];
        foreach ($arrivalDomestik as $hasil) {
            $flightNumber = $hasil["flight_number"];
            $origin = $hasil["airport_code"];
            $airplaneType = $hasil["airplane_type"];
            $scheduleTime = $hasil["schedule_time"];
            $belt = $hasil["belt"];

            array_push($arrayDomestikArrival, [$flightNumber, $origin, $airplaneType, $scheduleTime, $belt]);
        }

        $arrivalInternasional = ArrivalFlightModel::whereDate('schedule_time', $request->exportDate)->where('flightType', 'Internasional')
            ->join('tb_airport', 'tb_arrival.id_origin', '=', 'tb_airport.id_aiport')
            ->join('tb_belt', 'tb_arrival.belt', '=', 'tb_belt.id')
            ->orderBy('schedule_time', 'ASC')
            ->get()->toArray();

        $arrayInternasionalArrival = [];
        foreach ($arrivalInternasional as $hasil) {
            $flightNumber = $hasil["flight_number"];
            $origin = $hasil["airport_code"];
            $airplaneType = $hasil["airplane_type"];
            $scheduleTime = $hasil["schedule_time"];
            $belt = $hasil["belt"];

            array_push($arrayInternasionalArrival, [$flightNumber, $origin, $airplaneType, $scheduleTime, $belt]);
        }

        $export = new ExportFlightData($arrayDomestikDeparture, $arrayInternasionalDeparture, $arrayDomestikArrival, $arrayInternasionalArrival);

        return Excel::download($export, 'FlightData' . $request->exportDate . '.xlsx');
    }

    public function pdf()
    {
        $pdf = PDF::loadView('resume');
    }
}