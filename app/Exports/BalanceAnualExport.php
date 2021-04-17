<?php 
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BalanceAnualExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable;
    
    private $datos;

    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function headings(): array
    {
        return [
            'Concepto',
            'ING ENE',
            'EGR ENE',
            'ING FEB',
            'EGR FEB',
            'ING MAR',
            'EGR MAR',
            'ING ABR',
            'EGR ABR',
            'ING MAY',
            'EGR MAY',
            'ING JUN',
            'EGR JUN',
            'ING JUL',
            'EGR JUL',
            'ING AGO',
            'EGR AGO',
            'ING SEP',
            'EGR SEP',
            'ING OCT',
            'EGR OCT',
            'ING NOV',
            'EGR NOV',
            'ING DIC',
            'EGR DIC',
        ];
    }

    public function array(): array
    {
        return json_decode(json_encode($this->datos));
    }
}