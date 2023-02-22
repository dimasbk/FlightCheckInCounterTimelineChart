<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportFlightData implements WithMultipleSheets
{
    use Exportable;

    protected $domestik;
    protected $internasional;

    public function __construct(array $domestik, array $internasional)
    {
        $this->domestik = $domestik;
        $this->internasional = $internasional;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new FlightExportDomestic($this->domestik),
            new FlightExportInternational($this->internasional)
        ];

        return $sheets;
    }
}