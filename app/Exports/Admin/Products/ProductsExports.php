<?php

namespace App\Exports\Admin\Products;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;
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

class ProductsExports implements FromCollection, WithHeadings, WithStyles, WithMapping, ShouldAutoSize, WithEvents
{
    public $count;

    /**
     * Prepare Collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $products = Product::select(
            'id',
            'name',
            'barcode',
            'weight',
            'quantity',
            'low_stock',
            'base_price',
            'final_price',
            'points',
            'model',
            'refundable',
            'free_shipping',
            'publish',
            'under_reviewing',
            'created_by',
            'brand_id',
            'created_at',
            'updated_at'
        )->with(
            [
                'brand' => function ($q) {
                    return $q->select('id', 'name')->with('country');
                },
                'subcategories' => function ($q) {
                    return $q->select('subcategories.id', 'subcategories.name', 'category_id')
                        ->with(['category' => function ($q) {
                            return $q->with('supercategory');
                        }]);
                },
                'user' => function ($q) {
                    return $q->select('id', 'f_name', 'l_name');
                }
            ]
        )->get();
        $this->count = $products->count();

        return $products;
    }

    // Customize Header
    public function headings(): array
    {
        return [
            [__('admin/productsPages.Products Data')],
            [
                __('admin/productsPages.Name (En)'),
                __('admin/productsPages.Name (Ar)'),
                __('admin/productsPages.Model'),
                __('admin/productsPages.Barcode'),
                __('admin/productsPages.Brand'),
                __('admin/productsPages.Country'),
                __('admin/productsPages.Subcategory'),
                __('admin/productsPages.Category'),
                __('admin/productsPages.Supercategory'),
                __('admin/productsPages.Base Price'),
                __('admin/productsPages.Final Price'),
                __('admin/productsPages.Discount'),
                __('admin/productsPages.Points'),
                __('admin/productsPages.Under Reviewing'),
                __('admin/productsPages.Weight'),
                __('admin/productsPages.Quantity'),
                __('admin/productsPages.Low Stock Limit'),
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
    public function map($product): array
    {
        return [
            $product->getTranslation('name', 'en'),
            $product->getTranslation('name', 'ar'),
            $product->model ?? __('N/A'),
            $product->barcode ?? __('N/A'),
            $product->brand ? $product->brand->name : __('N/A'),
            $product->brand && $product->brand->country ? $product->brand->country->name : __('N/A'),
            $product->subcategories->first() ? $product->subcategories->first()->getTranslation('name', session('locale')) : __('N/A'),
            $product->subcategories->first() ? ($product->subcategories->first()->category ? $product->subcategories->first()->category->getTranslation('name', session('locale')) : __('N/A')) : __('N/A'),
            $product->subcategories->first() ? ($product->subcategories->first()->category ? ($product->subcategories->first()->category->supercategory ? $product->subcategories->first()->category->supercategory->getTranslation('name', session('locale')) : __('N/A')) : __('N/A')) : __('N/A'),
            $product->base_price ??  __('N/A'),
            $product->final_price ??  __('N/A'),
            $product->final_price && $product->base_price ? round((100 * ($product->base_price - $product->final_price)) / $product->base_price, 2) . '%' :  '0%',
            $product->points ?? 0,
            $product->under_reviewing ? __('Yes') :  __('No'),
            $product->weight ?? 0,
            $product->quantity ?? 0,
            $product->low_stock ?? 0,
            $product->refundable ? __('Yes') :  __('No'),
            $product->free_shipping ? __('Yes') :  __('No'),
            $product->publish ? __('Yes') :  __('No'),
            $product->user ? $product->user->getTranslation('f_name', session('locale')) . " " . $product->user->getTranslation('l_name', session('locale')) : __('N/A'),
            $product->created_at ? Carbon::createFromTimeStamp(strtotime($product->created_at))->format('m/d/Y') : __('N/A'),
            $product->updated_at ? Carbon::createFromTimeStamp(strtotime($product->updated_at))->format('m/d/Y') : __('N/A'),
        ];
    }

    // Style Sheet
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:W1');
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

            'A1:W' . ($this->count + 2) => [
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
