<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StaffWiseSaleExport  implements FromCollection, WithMapping, WithHeadings
{
    use PreventDemoModeChanges;

    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return $this->sales;
    }

    public function headings(): array
    {
        return [
            'Staff Name',
            'Staff Number/Email',
            'Total Sales',
            'Total Amount',
        ];
    }

    /**
    * @var Order  $order
    */
    public function map($sales): array
    {
        return [
            $sales['name'],
            $sales['number']?? $sales['email'],
            $sales['total_sales'],
            $sales['total_amount'],
        ];
    }
}