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
use App\Models\LabourWorker;

class LabourExport extends PrivacyValueBinder implements FromView, ShouldAutoSize, WithEvents, \Maatwebsite\Excel\Concerns\WithCustomValueBinder
{
    use Exportable;

    protected $date;
    protected $project;

    function __construct($date,$project) {
        $this->date = $date;
        $this->project = $project;
    }

    public function view(): View
    {
        if(!empty($this->date))
        {
            $where['ltracker_date'] = $this->date;
        }
        else
        {
            $where['ltracker_date'] = date('Y-m-d');
        }
        
        if(!empty($this->project))
        {
            $where['ltracker_project'] = $this->project;
        }

        $data['workers'] = LabourWorker::join('labour_trackers','labour_trackers.ltracker_id','labour_workers.lworker_tracker')->join('projects','projects.project_id','labour_trackers.ltracker_project')->join('subcontractors','subcontractors.subcontractor_id','labour_trackers.ltracker_subcontractor')->join('category','category.category_id','labour_workers.lworker_category')->where($where)->get();

        return view('user.labour_tracker.labours_download_excel',$data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A1:P1')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('D3D3D3');
                $event->sheet->getDelegate()->getStyle('A1:P1')
                ->getFont()
                ->setBold(true);

            },
        ];
    }
}
