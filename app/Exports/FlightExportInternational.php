<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FlightExportInternational implements FromArray, WithHeadings, WithTitle
{
    protected $internasional;

    public function __construct(array $internasional)
    {
        $this->internasional = $internasional;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        return $this->internasional;
    }

    public function title(): string
    {
        return 'International Departure';
    }


    public function headings(): array
    {
        return [
            'Flight Number',
            'Destination',
            'Type',
            'Schedule Time',
            'Desk',
            'Gate',
            'Total Pax',
            'Jumlah CIC',
        ];
    }
}