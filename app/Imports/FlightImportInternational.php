<?php

namespace App\Imports;

use App\Models\CheckinDeskModel;
use App\Models\FlightModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\AirlineModel;
use App\Models\AirportCodeModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class FlightImportInternational implements ToModel, WithStartRow, WithCalculatedFormulas
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
        for ($i = $explodedID[0]; $i <= $explodedID[1]; $i++) {
            $id = CheckinDeskModel::where('checkin_desk', $i)->value('id');
            array_push($array, $id);
        }
        $id_checkin_desk = implode(",", $array);

        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]);
        //dd($date);
        $data = FlightModel::where('flight_number', $row[0])->where('schedule_time', $date)->first();
        if ($data === null) {
            return new FlightModel([
                'flight_number' => $row[0],
                'id_destination' => $airportCode,
                'airplane_type' => $row[2],
                'id_airline' => $airline,
                'schedule_time' => $date,
                'id_checkin_desk' => $id_checkin_desk,
                'gate' => $row[10],
                'pax' => $row[30],
                'cic' => $row[31],
                'flightType' => "Internasional"
            ]);
        }
    }
}