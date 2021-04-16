<?php 
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductoExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return ['codigo','descripcion','marca','precio','stock'];
    }

    public function array(): array
    {
        $query="SELECT  P.codigo,
                        P.descripcion,
                        P.marca,
                        P.precio,
                        COALESCE(SUM(IF(S.tipo='I',1,-1)*S.cantidad),0) stock 
                FROM producto P
                LEFT JOIN stock S on S.producto_id=P.id
                WHERE P.tipo='P'";
        return json_decode(json_encode(DB::select($query)));
        // return Producto::select('nombres','ape_paterno','ape_materno','telefono','email','direccion','fecha_nacimiento')->get();
    }
}