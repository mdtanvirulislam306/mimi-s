<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithMapping, WithHeadings
{
    use PreventDemoModeChanges;

    protected $order_ids;
    protected $index = 0; // counter

    public function __construct($order_ids)
    {
        $this->order_ids = $order_ids;
    }

    public function collection()
    {
        return Order::with('user')->findMany($this->order_ids);
    }

    public function headings(): array
    {
        return [
            'SL',
            'Date',
            'Invoice No',
            'Customer Name',
            'Customer Number',
            'Total Amount',
            'Customer Address'
        ];
    }

    /**
    * @var Order $order
    */
    public function map($order): array
    {
        $this->index++;

        return [
            $this->index, 
            date('d-m-Y h:i A', strtotime($order->created_at)),
            $order->code,
            $order->user->name ??  (json_decode($order->shipping_address)->name ?? ''),
            $order->user->phone ?? (json_decode($order->shipping_address)->phone ?? ''),
            $order->grand_total,
            json_decode($order->shipping_address)->address ?? '' 
        ];
    }
}
