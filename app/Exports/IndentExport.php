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
use App\Models\IndentItem;

class IndentExport extends PrivacyValueBinder implements FromView, ShouldAutoSize, WithEvents, \Maatwebsite\Excel\Concerns\WithCustomValueBinder
{
    use Exportable;

    protected $indent_id;

    function __construct($indent_id) {
        $this->indent_id = $indent_id;
    }

    public function view(): View
    {
        $where['iitem_indent'] = $this->indent_id;
        $data['items'] = IndentItem::where($where)->get();

        return view('user.indent.indent_download_excel',$data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:K1')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('D3D3D3');
                $event->sheet->getDelegate()->getStyle('A1:K1')
                ->getFont()
                ->setBold(true);

            },
        ];
    }
}
