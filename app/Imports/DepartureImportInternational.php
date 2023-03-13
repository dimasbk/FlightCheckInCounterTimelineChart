<?php

namespace App\Imports;

use App\Models\CheckinDeskModel;
use App\Models\DepartureFlightModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\AirlineModel;
use App\Models\AirportCodeModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\GateModel;

class DepartureImportInternational implements ToModel, WithStartRow, WithCalculatedFormulas
{

    public function startRow(): int
    {
        return 2;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $airlineCode = substr($row[0], 0, 2);

        $airline = AirlineModel::where('code', $airlineCode)->value('id_airline');
        $airportCode = AirportCodeModel::where('airport_code', $row[1])->value('id_aiport');
        $checkinDesk = $row[9];
        $explodedID = explode("-", $checkinDesk);
        $array = [];
        $idFirst = CheckinDeskModel::where('checkin_desk', $explodedID[0])->value('id');
        $idLast = CheckinDeskModel::where('checkin_desk', $explodedID[1])->value('id');
        for ($i = $idFirst; $i <= $idLast; $i++) {
            array_push($array, $i);
        }
        $id_checkin_desk = implode(",", $array);

        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]);
        $gate = GateModel::where('gate', $row[10])->value('id');
        //dd($gate);
        //dd($date);
        $data = DepartureFlightModel::where('flight_number', $row[0])->where('schedule_time', $date)->first();
        if ($data === null) {
            return new DepartureFlightModel([
                'flight_number' => $row[0],
                'id_destination' => $airportCode,
                'airplane_type' => $row[2],
                'id_airline' => $airline,
                'schedule_time' => $date,
                'id_checkin_desk' => $id_checkin_desk,
                'gate' => $gate,
                'pax' => $row[30],
                'cic' => $row[31],
                'flightType' => "Internasional"
            ]);
        }
    }
}