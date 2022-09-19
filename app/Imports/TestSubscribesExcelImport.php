<?php

namespace App\Imports;

use App\Models\Subscribe;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;

use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TestSubscribesExcelImport extends DefaultValueBinder implements ToModel,
    WithCustomValueBinder, SkipsEmptyRows
{
    use Importable;

    // set the preferred date format
    private $date_format = 'Y-m-d H:i';
    private $month_day_format = 'd';

    // set the columns to be formatted as dates
    private $date_columns = ['B'];

    // set the columns to be formatted as dates
    private $month_day_columns = ['J','K','L','M','N','O','P','Q','R','S','T','U','V','W'];

    // bind date formats to column defined above
    public function bindValue(Cell $cell, $value)
    {
        if (in_array($cell->getColumn(), $this->date_columns)) {
            $cell->setValueExplicit(Date::excelToDateTimeObject($value)->format($this->date_format), DataType::TYPE_STRING);
            return true;
        } elseif (in_array($cell->getColumn(), $this->month_day_columns)) {
            $cell->setValueExplicit(Date::excelToDateTimeObject($value)->format($this->month_day_format), DataType::TYPE_STRING);
            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }



    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Subscribe([

        ]);
    }
}
