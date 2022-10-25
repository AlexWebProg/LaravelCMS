<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use \Maatwebsite\Excel\Writer;

use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SubscribesExcelExport implements FromArray,
    WithHeadings, WithColumnFormatting, WithDefaultStyles, ShouldAutoSize, WithEvents, WithColumnWidths
{

    protected $arHeadings;
    protected $arData;

    public function __construct($arHeadings, $arData)
    {
        $this->arHeadings = $arHeadings;
        $this->arData = $arData;
    }

    public function array(): array
    {
        return $this->arData;
    }

    public function columnWidths(): array
    {
        return [
            'I' => 25,
            'J' => 25,
        ];
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents():array
    {
        Writer::macro('setDefaultStyle', function (Writer $writer) {
            $writer->getActiveSheet()->freezePane('A2');
            $writer->getActiveSheet()->setAutoFilter('A1:V1');

            $writer->getActiveSheet()->setTitle('Подписки BLACKBASE');
            $writer->getActiveSheet()->getStyle('A:C')->getAlignment()->setHorizontal('center');
            $writer->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal('right');
            $writer->getActiveSheet()->getStyle('E:U')->getAlignment()->setHorizontal('center');
        });

        return [
            BeforeWriting::class=>function(BeforeWriting $event){
                $event->writer->setDefaultStyle();
            },
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        // Or return the styles array
        return [
            'font' => [
                'size'   => 8,
                'name' => 'Arial'
            ],
        ];
    }

    public function headings(): array
    {
        return $this->arHeadings;
    }

    public function columnFormats(): array
    {
        return [
            'D' => '0.00',
        ];
    }

}
