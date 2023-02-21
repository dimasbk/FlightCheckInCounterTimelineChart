<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ImportFlightData implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new FlightImportInternational(),
            1 => new FlightImportDomestic(),
        ];
    }
}