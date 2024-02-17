<?php

namespace App\Exports\Admin\Products;

use Carbon\Carbon;
use App\Models\Product;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Carbon as SupportCarbon;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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
            [
                __('admin/productsPages.Products Data'),
            ],
            [
                "ID",
                __('admin/productsPages.Name (En)'),
                __('admin/productsPages.Name (Ar)'),
                __('admin/productsPages.Model'),
                __('admin/productsPages.Barcode'),
                __('admin/productsPages.Brand'),
                __('admin/productsPages.Country'),
                __('admin/productsPages.Subcategory'),
                __('admin/productsPages.Category'),
                __('admin/productsPages.Supercategory'),
                __('admin/productsPages.Original Price'),
                __('admin/productsPages.Base Price'),
                __('admin/productsPages.Final Price'),
                __('admin/productsPages.Discount'),
                __('admin/productsPages.Points'),
                __('admin/productsPages.Weight'),
                __('admin/productsPages.Quantity'),
                __('admin/productsPages.Low Stock Limit'),
                __('admin/productsPages.Under Reviewing'),
                __('admin/productsPages.Refundable'),
                __('admin/productsPages.Free Shipping'),
                __('admin/productsPages.Published'),
                __('admin/productsPages.Created By'),
                __('admin/productsPages.Created at'),
                __('admin/productsPages.Updated at'),
            ],
        ];
    }

    // Mapping data according to header
    public function map($product): array
    {
        return [
            $product->id,
            $product->getTranslation('name', 'en'),
            $product->getTranslation('name', 'ar'),
            $product->model ?? __('N/A'),
            $product->barcode ?? __('N/A'),
            $product->brand ? $product->brand->name : __('N/A'),
            $product->brand && $product->brand->country ? $product->brand->country->name : __('N/A'),
            $product->subcategories->first() ? $product->subcategories->first()->getTranslation('name', session('locale')) : __('N/A'),
            $product->subcategories->first() ? ($product->subcategories->first()->category ? $product->subcategories->first()->category->getTranslation('name', session('locale')) : __('N/A')) : __('N/A'),
            $product->subcategories->first() ? ($product->subcategories->first()->category ? ($product->subcategories->first()->category->supercategory ? $product->subcategories->first()->category->supercategory->getTranslation('name', session('locale')) : __('N/A')) : __('N/A')) : __('N/A'),
            $product->original_price ?? 0.00,
            $product->base_price ?? 0.00,
            $product->final_price ?? 0.00,
            $product->final_price && $product->base_price && $product->base_price > 0 ? round((100 * ($product->base_price - $product->final_price)) / $product->base_price, 2) . '%' :  '0%',
            $product->points ?? 0,
            $product->weight ?? 0,
            $product->quantity ?? 0,
            $product->low_stock ?? 0,
            $product->under_reviewing ? __('Yes') :  __('No'),
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
        $sheet->mergeCells('A1:Y1');
        $sheet->getDefaultRowDimension()->setRowHeight(25);
        $sheet->getPageSetup()->setOrientation('landscape');

        return [
            'A1:Y2' => [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'ffffff'
                    ]
                ]
            ],

            "A1:Y1" => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'ba0024',
                    ]
                ]
            ],

            "A2:Y2" => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => '333333',
                    ]
                ]
            ],

            'B3:B' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'ecf0f1', // Specify your desired color here
                    ]
                ]
            ],

            'C3:C' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'd4d8d9', // Specify your desired color here
                    ]
                ]
            ],

            'D3:D' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'ecf0f1', // Specify your desired color here
                    ]
                ]
            ],

            'E3:E' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'd4d8d9', // Specify your desired color here
                    ]
                ]
            ],

            'K3:K' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'ecf0f1', // Specify your desired color here
                    ]
                ]
            ],

            'L3:L' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'd4d8d9', // Specify your desired color here
                    ]
                ]
            ],

            'M3:M' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'ecf0f1', // Specify your desired color here
                    ]
                ]
            ],

            'O3:O' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'd4d8d9', // Specify your desired color here
                    ]
                ]
            ],

            'P3:P' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'ecf0f1', // Specify your desired color here
                    ]
                ]
            ],

            'Q3:Q' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'd4d8d9', // Specify your desired color here
                    ]
                ]
            ],

            'R3:R' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'ecf0f1', // Specify your desired color here
                    ]
                ]
            ],

            'S3:S' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'd4d8d9', // Specify your desired color here
                    ]
                ]
            ],

            'T3:T' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'ecf0f1', // Specify your desired color here
                    ]
                ]
            ],

            'U3:U' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'd4d8d9', // Specify your desired color here
                    ]
                ]
            ],

            'V3:V' . ($this->count + 2) => [ // Start from the 3rd row in the 2nd column
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => [
                        'rgb' => 'ecf0f1', // Specify your desired color here
                    ]
                ]
            ],

            'A1:Y' . ($this->count + 2) => [
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
                AfterSheet::class => function (AfterSheet $event) {
                    $event->sheet->getDelegate()->setRightToLeft(true);

                    $this->addFilter($event, ["S", "T", "U", "V"]);
                },
            ];
        }
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->addFilter($event, ["S", "T", "U", "V"]);
            }
        ];
    }

    // Add "Yes"|"No" filter to Yes/No columns (S,T,U & V)
    private function addFilter(AfterSheet $event, array $columns)
    {
        // get layout counts (add 1 to rows for heading row)
        $row_count = $this->count + 2;

        // set dropdown column
        $drop_column = $columns[0];

        // set dropdown options
        $options = [
            __('Yes'),
            __('No')
        ];

        // set dropdown list for first data row
        $validation = $event->sheet->getCell("{$drop_column}2")->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle(__('Input error'));
        $validation->setError(__('Value is not in list.'));
        $validation->setPromptTitle(__('Pick from list'));
        $validation->setPrompt(__('Please pick a value from the drop-down list.'));
        $validation->setFormula1(sprintf('"%s"', implode(',', $options)));

        // clone validation to remaining rows
        foreach ($columns as $column) {
            for ($i = 3; $i <= $row_count; $i++) {
                $event->sheet->getCell("{$column}{$i}")->setDataValidation(clone $validation);
            }
        }
    }
}
