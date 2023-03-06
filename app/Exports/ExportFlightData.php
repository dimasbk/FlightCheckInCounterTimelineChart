<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportFlightData implements WithMultipleSheets
{
    use Exportable;

    protected $domestikDeparture;
    protected $internasionalDeparture;
    protected $domestikArrival;

    protected $internasionalArrival;

    public function __construct(array $domestikDeparture, array $internasionalDeparture, array $domestikArrival, array $internasionalArrival)
    {
        $this->domestikDeparture = $domestikDeparture;
        $this->internasionalDeparture = $internasionalDeparture;
        $this->domestikArrival = $domestikArrival;
        $this->internasionalArrival = $internasionalArrival;

    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new DepartureInternational($this->internasionalDeparture),
            new DepartureDomestic($this->domestikDeparture),
            new ArrivalInternational($this->internasionalArrival),
            new ArrivalDomestic($this->domestikArrival)
        ];

        return $sheets;
    }
}