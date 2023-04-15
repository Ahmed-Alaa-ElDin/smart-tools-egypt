<?php

namespace App\Exports\Admin\Collections;

use App\Models\Collection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CollectionsExport implements FromCollection, WithHeadings, WithStyles, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $collections = Collection::select(
            'id',
            'name',
            'barcode',
            'weight',
            'original_price',
            'base_price',
            'final_price',
            'points',
            'model',
            'refundable',
            'free_shipping',
            'publish',
            'under_reviewing',
            'created_by',
            'created_at',
            'updated_at'
        )->with(
            [
                'products' => fn ($q) => $q->select('products.id'),
                'user' => fn ($q) => $q->select('users.id', 'f_name', 'l_name')
            ]
        )
        ->withCount('products')
        ->get();

        $this->count = $collections->count();

        return $collections;
    }

    // Customize Header
    public function headings(): array
    {
        return [
            [__('admin/productsPages.Collections Data')],
            [
                __('admin/productsPages.Name (En)'),
                __('admin/productsPages.Name (Ar)'),
                __('admin/productsPages.No. of Products'),
                __('admin/productsPages.Model'),
                __('admin/productsPages.Barcode'),
                __('admin/productsPages.Under Reviewing'),
                __('admin/productsPages.Original Price'),
                __('admin/productsPages.Base Price'),
                __('admin/productsPages.Final Price'),
                __('admin/productsPages.Discount'),
                __('admin/productsPages.Points'),
                __('admin/productsPages.Weight'),
                __('admin/productsPages.Refundable'),
                __('admin/productsPages.Free Shipping'),
                __('admin/productsPages.Published'),
                __('admin/productsPages.Created By'),
                __('admin/productsPages.Created at'),
                __('admin/productsPages.Updated at'),
            ]
        ];
    }

    // Mapping data according to header
    public function map($collection): array
    {
        return [
            $collection->getTranslation('name', 'en'),
            $collection->getTranslation('name', 'ar'),
            $collection->products_count ?? '0',
            $collection->model ?? __('N/A'),
            $collection->barcode ?? __('N/A'),
            $collection->under_reviewing ? __('Yes') :  __('No'),
            $collection->original_price ??  __('N/A'),
            $collection->base_price ??  __('N/A'),
            $collection->final_price ??  __('N/A'),
            $collection->final_price && $collection->base_price ? round((100 * ($collection->base_price - $collection->final_price)) / $collection->base_price, 2) . '%' :  '0%',
            $collection->points ?? '0',
            $collection->weight ?? '0',
            $collection->refundable ? __('Yes') :  __('No'),
            $collection->free_shipping ? __('Yes') :  __('No'),
            $collection->publish ? __('Yes') :  __('No'),
            $collection->user ? $collection->user->getTranslation('f_name', session('locale')) . " " . $collection->user->getTranslation('l_name', session('locale')) : __('N/A'),
            $collection->created_at ? Carbon::createFromTimeStamp(strtotime($collection->created_at))->format('m/d/Y') : __('N/A'),
            $collection->updated_at ? Carbon::createFromTimeStamp(strtotime($collection->updated_at))->format('m/d/Y') : __('N/A'),
        ];
    }

    // Style Sheet
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:R1');
        $sheet->getDefaultRowDimension()->setRowHeight(25);
        $sheet->getPageSetup()->setOrientation('landscape');

        return [
            '1:2' => [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'ffffff'
                    ]
                ]
            ],

            1 => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'ba0024',
                    ]
                ]
            ],

            2 => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => '333333',
                    ]
                ]
            ],

            'A1:R' . ($this->count + 2) => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ]
                ],
            ],

        ];
    }

    // Set Sheet Direction
    public function registerEvents(): array
    {
        if (LaravelLocalization::getCurrentLocale() == 'ar') {
            return [
                AfterSheet::class    => function (AfterSheet $event) {
                    $event->sheet->getDelegate()->setRightToLeft(true);
                },
            ];
        }
        return [];
    }
}
