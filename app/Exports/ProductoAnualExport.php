<?php 
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductoAnualExport implements FromArray, WithHeadings, ShouldAutoSize
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
            'CODIGO',
            'PRODUCTO',
            'ENERO',
            'FEBRERO',
            'MARZO',
            'ABRIL',
            'MAYO',
            'JUNIO',
            'JULIO',
            'AGOSTO',
            'SEPTIEMBRE',
            'OCTUBRE',
            'NOVIEMBRE',
            'DICIEMBRE'
        ];
    }

    public function array(): array
    {
        return json_decode(json_encode($this->datos));
    }
}