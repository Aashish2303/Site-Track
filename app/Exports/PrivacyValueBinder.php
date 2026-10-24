<?php

namespace App\Exports;

use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

/** Free text must not become an executable Excel formula or external data request. */
class PrivacyValueBinder extends DefaultValueBinder
{
    public function bindValue(Cell $cell, $value)
    {
        if (is_string($value) && preg_match('/^[\s\x{FEFF}]*[=+@-]/u', $value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
