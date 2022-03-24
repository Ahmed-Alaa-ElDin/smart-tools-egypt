<?php

namespace App\Exports\Admin\Users;

use App\Models\User;
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

class UsersExport implements FromCollection, WithHeadings, WithStyles, WithMapping, ShouldAutoSize, WithEvents
{
    public $count;

    /**
     * Prepare Collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $users = User::with('roles','phones','addresses')->get();
        $this->count = $users->count();

        return $users;
    }

    // Customize Header
    public function headings(): array
    {
        return [
            [__('admin/usersPages.Users Data')],
            [
                __('admin/usersPages.First Name (En)'),
                __('admin/usersPages.Last Name (En)'),
                __('admin/usersPages.First Name (Ar)'),
                __('admin/usersPages.Last Name (Ar)'),
                __('admin/usersPages.Email'),
                __('admin/usersPages.Phone'),
                __('admin/usersPages.Gender'),
                __('admin/usersPages.Balance'),
                __('admin/usersPages.Points'),
                __('admin/usersPages.Governorate (En)'),
                __('admin/usersPages.Governorate (Ar)'),
                __('admin/usersPages.Role'),
                __('admin/usersPages.Visits Count'),
                __('admin/usersPages.Last Visit'),
                __('admin/usersPages.Created from'),
            ]
        ];
    }

    // Mapping data according to header
    public function map($user): array
    {
        return [
            $user->getTranslation('f_name', 'en'),
            $user->getTranslation('l_name', 'en'),
            $user->getTranslation('f_name', 'ar'),
            $user->getTranslation('l_name', 'ar'),
            $user->email,
            $user->phones->first() ? $user->phones->where('default',1)->first()->phone : __('N/A'),
            $user->gender == 0 ? __('Male') : __('Female'),
            $user->balance == 0 ? "0" . ' ' . __('admin/usersPages.LE') : $user->balance . ' ' . __('admin/usersPages.LE'),
            $user->points == 0 ? "0" : $user->points,
            $user->addresses->first() && $user->addresses->first()->governorate ? $user->addresses->first()->governorate->getTranslation('name','en') : __('N/A'),
            $user->addresses->first() && $user->addresses->first()->governorate ? $user->addresses->first()->governorate->getTranslation('name','ar') : __('N/A'),
            $user->getRoleNames()->first(),
            $user->visit_num ?: "0",
            $user->last_visit_at ? Carbon::createFromTimeStamp(strtotime($user->last_visit_at))->diffForHumans() : __('N/A'),
            $user->created_at ? $user->created_at->diffForHumans() : __('N/A'),
        ];
    }

    // Style Sheet
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:O1');
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

            'A1:O' . ($this->count + 2) => [
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
