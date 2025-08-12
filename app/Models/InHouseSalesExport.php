<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InHouseSalesExport implements FromCollection, WithMapping, WithHeadings
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
            'Product Name',
            'Branch',
            'Number of Sales',
        ];
    }

    /**
    * @var Order  $order
    */
    public function map($product): array
    {
        //dd($product);
        return [
            $product['name'],
            $product['branch'] ?? 'N/A',
            $product['sales_count'],
        ];
    }
}