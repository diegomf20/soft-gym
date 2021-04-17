<?php 
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MovimientoExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable;
    
    private $header;
    private $datos;

    public function __construct($header,$datos)
    {
        $this->header = $header;
        $this->datos = $datos;
    }

    public function headings(): array
    {
        return $this->header;
    }

    public function array(): array
    {
        return json_decode(json_encode($this->datos));
    }
}