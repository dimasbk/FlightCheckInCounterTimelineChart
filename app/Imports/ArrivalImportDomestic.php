<?php

namespace App\Imports;

use App\Models\ArrivalFlightModel;
use App\Models\AirlineModel;
use App\Models\AirportCodeModel;
use App\Models\BeltModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ArrivalImportDomestic implements ToModel, WithStartRow
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
        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]);
        $data = ArrivalFlightModel::where('flight_number', $row[0])->where('schedule_time', $date)->first();
        $beltID = BeltModel::where('belt', $row[11])->value('id');
        if ($data === null) {
            return new ArrivalFlightModel([
                'flight_number' => $row[0],
                'id_origin' => $airportCode,
                'airplane_type' => $row[2],
                'id_airline' => $airline,
                'schedule_time' => $date,
                'belt' => $beltID,
                'flightType' => "Domestik"
            ]);
        }
    }
}