<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use Auth;
use DB;
use App\Models\Vendor;

class VendorExport implements FromView, ShouldAutoSize, WithEvents
{
    use Exportable;

    protected $category;

    function __construct($category) {
        $this->category = $category;
    }

    public function view(): View
    {
        $where['vendor_trash'] = 1;
        $data['vendors'] = Vendor::where($where)->orderby('vendor_id','desc')->get();

        return view('user.vendor.vendors_download_excel',$data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:I1')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('D3D3D3');
                $event->sheet->getDelegate()->getStyle('A1:I1')
                ->getFont()
                ->setBold(true);

            },
        ];
    }
}
