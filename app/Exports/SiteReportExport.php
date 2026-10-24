<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class SiteReportExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder
{
    public function __construct(private array $data) {}

    public function view(): View
    {
        return view('user.reports.excel', $this->data);
    }

    public function bindValue(Cell $cell, $value): bool
    {
        // Project/material names must never become spreadsheet formulas.
        if (is_string($value) && !is_numeric($value) && preg_match('/^[\s]*[=+@-]/u', $value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }
}
