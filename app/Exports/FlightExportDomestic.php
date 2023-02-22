<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FlightExportDomestic implements FromArray, WithHeadings, WithTitle
{
    protected $domestik;

    public function __construct(array $domestik)
    {
        $this->domestik = $domestik;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        return $this->domestik;
    }

    public function title(): string
    {
        return 'Domestic Departure';
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