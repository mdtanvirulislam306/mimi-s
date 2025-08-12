<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailySalesExport implements FromCollection, WithMapping, WithHeadings
{
    use PreventDemoModeChanges;

    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return  $this->sales;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Total Sales',
            'Total Amount',
        ];
    }

    /**
    * @var Order  $order
    */
    public function map($sale): array
    {
        return [
            \Carbon\Carbon::parse($sale->date)->format('d M Y'),
            $sale->total_sale,
            $sale->grand_total,
        ];
    }
}