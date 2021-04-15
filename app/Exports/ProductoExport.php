<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductoExport implements FromView, WithColumnFormatting, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;
 
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function columnFormats(): array
    {
        return [
            // 'B' => NumberFormat::FORMAT_TEXT
        ];
    }

    public function view(): View
    {
        
        return view('excel.producto', [
            'productos' => $this->data
        ]);
    }
}
