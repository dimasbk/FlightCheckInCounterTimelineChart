<?php

namespace App\Imports;

use App\Models\DepartureFlightModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\AirlineModel;
use App\Models\AirportCodeModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class DepartureImportDomestic implements ToModel, WithStartRow, WithCalculatedFormulas
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

        $airline = AirlineModel::where('code', $airlineCode)->pluck('id_airline')[0];
        $airportCode = AirportCodeModel::where('airport_code', $row[1])->pluck('id_aiport')[0];
        $checkinDesk = $row[9];
        $explodedID = explode("-", $checkinDesk);
        $array = [];
        for ($i = $explodedID[0]; $i <= $explodedID[1]; $i++) {
            array_push($array, $i);
        }
        $id_checkin_desk = implode(",", $array);
        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]);
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
                'gate' => $row[10],
                'pax' => $row[30],
                'cic' => $row[31],
                'flightType' => "Domestik"
            ]);
        }
    }
}