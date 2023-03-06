<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ImportFlightData implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new DepartureImportInternational(),
            1 => new DepartureImportDomestic(),
            2 => new ArrivalImportInternational(),
            3 => new ArrivalImportDomestic()
        ];
    }
}