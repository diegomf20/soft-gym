<?php 
namespace App\Exports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClienteExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return ['nombres','apellido paterno','apellido materno','telefono','email','direccion','fecha_nacimiento'];
    }

    public function collection()
    {
        return Cliente::select('nombres','ape_paterno','ape_materno','telefono','email','direccion','fecha_nacimiento')->get();
    }
}