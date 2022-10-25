<?php

namespace App\Http\Controllers\Admin\SubscribeConsist;

use App\Http\Requests\Admin\Subscribe\PDFExportRequest;
use App\Models\Subscribe;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFExportController extends BaseController
{
    public function __invoke(PDFExportRequest $request, $month)
    {
        $arData = $request->validated();
        $arSubscribes = [];
        foreach ($arData['subscribe_id'] as $intSubscribeId) {
            $arSubscribes[] = Subscribe::find($intSubscribeId);
        }
        $strMonth = monthIntToStr($month);
        $arMonthForFileName = explode('.',$month);
        $strMonthForFileName = 'SubscribeConsist_'.$arMonthForFileName[1].'_'.$arMonthForFileName[0];

        $pdf = Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('admin.subscribeConsist.pdf', [
            'month_int' => $month,
            'month_str' => $strMonth,
            'subscribes' => $arSubscribes,
            'title' => $strMonthForFileName
        ]);

        return $pdf->download($strMonthForFileName.'.pdf');

        //return view('admin.subscribeConsist.pdf', compact('month', 'strMonth', 'arSubscribes', 'strMonthForFileName'));
    }
}
