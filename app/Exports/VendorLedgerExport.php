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
use App\Models\VendorLedger;

class VendorLedgerExport extends PrivacyValueBinder implements FromView, ShouldAutoSize, WithEvents, \Maatwebsite\Excel\Concerns\WithCustomValueBinder
{
    use Exportable;

    public function __construct(private ?int $project = null) {}

    public function view(): View
    {
        $data['all_ledger'] = VendorLedger::when($this->project, fn ($q) => $q->where('vledger_project', $this->project))->orderby('vledger_id','desc')->get();

        return view('user.vendor_ledger.vendor_ledger_download_excel',$data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:L1')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('D3D3D3');
                $event->sheet->getDelegate()->getStyle('A1:L1')
                ->getFont()
                ->setBold(true);

            },
        ];
    }
}
