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
use App\Models\DailyWork;

class DailyWorkExport extends PrivacyValueBinder implements FromView, ShouldAutoSize, WithEvents, \Maatwebsite\Excel\Concerns\WithCustomValueBinder
{
    use Exportable;

    protected $start_date;
    protected $end_date;
    protected $status;

    function __construct($start_date,$end_date,$status) {
        $this->start_date = $start_date;
        $this->end_date   = $end_date;
        $this->status     = $status;
    }

    public function view(): View
    {
        $where = 'dwork_trash = 1';

        if(!empty($this->start_date))
        {
            $where .= ' AND dwork_date >= "'.date('Y-m-d',strtotime($this->start_date)).'"';
        }

        if(!empty($this->end_date))
        {
            $where .= ' AND dwork_date <= "'.date('Y-m-d',strtotime($this->end_date)).'"';
        }

        if(!empty($this->status))
        {
            $where .= ' AND dwork_status = '.$this->status;
        }

        $data['daily_works'] = DailyWork::whereRaw($where)->orderby('dwork_id','desc')->get();

        return view('user.daily_work.works_download_excel',$data);
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
