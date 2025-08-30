<?php
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

if(! function_exists('settings')) {
    /**
     * Settings class helper
     *
     * @return App\Support\Settings
     */
    function settings()
    {
        return new App\Support\Settings;
    }
}

if(! function_exists('currency')) {
    /**
     * Currency class helper
     *
     * @return App\Support\Currency
     */
    function currency()
    {
        return new App\Support\CurrencyWrapper;
    }
}

if(! function_exists('counter')) {
    /**
     * Counter class helper
     *
     * @return App\Support\Counter
     */
    function counter()
    {
        return new App\Support\Counter;
    }
}

if(! function_exists('api')) {
    /**
     * Json response for API
     *
     * @param array $response
     * @param integer $code
     * @return App\Support\Counter
     */
    function api($response, $code = 200)
    {
        return response()
            ->json($response, $code);
    }
}

if(! function_exists('uploadFile')) {
    /**
     * Upload file to storage folder
     *
     * @param $file
     * @param $dir
     * @return string or null
     */
    function uploadFile($file, $dir = 'storage/app/uploads/')
    {
        $fileName = str_random(32).'.'.$file->extension();

        if($file->move(base_path($dir), $fileName)) {
            return $fileName;
        }

        return null;
    }
}

if(! function_exists('deleteFile')) {
    /**
     * Delete file from storage folder
     *
     * @param $file
     * @param $dir
     * @return string or null
     */
    function deleteFile($fileName, $dir = 'storage/app/uploads/')
    {
        return File::delete(base_path($dir).$fileName);
    }
}

if(! function_exists('pdf')) {
    /**
     * PDF output helper
     *
     * @param string $file
     * @param array $model
     */
    function pdf($file, $model)
    {

        $pdf = pdfRaw($file, $model);


        $file = $model->number.'-'.time().'.pdf';

        if(request()->has('mode') && request()->mode == 'download') {
            return $pdf->Output($file, Destination::DOWNLOAD);
        }

        return $pdf->Output($file, Destination::INLINE);
    }

    function pdfCataloguePortrait($file, $model)
    {


        // $pdf = pdfRaw($file, $model);
        $options = settings()->getMany(['uploaded_logo']);
        $options = array_filter($options, function($key){
            return !is_null($key);
        } );
        $html = view($file, ['model' => $model, 'options' => $options]);
        $pdf = new Mpdf(['mode' => 'utf-8',
                        'format' => 'A4', 
                        'orientation' => 'P',
                        'margin_left' => '0',
                        'margin_right' => '0',
                        'margin_top' => '2',
                        'margin_bottom' => '2',
                        'padding_left' => '0',
                        'padding_right' => '0',
                        'padding_top' => '0',
                        'padding_bottom' => '0',
                        ]);

        $pdf->WriteHTML($html);

        $file = 'pdfCatalogue -'.time().'.pdf';

        if(request()->has('mode') && request()->mode == 'download') {
            return $pdf->Output($file, Destination::DOWNLOAD);
        }

            return $pdf->Output($file, Destination::DOWNLOAD);
       //  return $pdf->Output($file, Destination::INLINE);
    }
    
    function pdfCatalogueLandscape($file, $model)
    {


        // $pdf = pdfRaw($file, $model);
        $options = settings()->getMany(['uploaded_logo']);
        $options = array_filter($options, function($key){
            return !is_null($key);
        } );
        $html = view($file, ['model' => $model, 'options' => $options]);
        $pdf = new Mpdf(['mode' => 'utf-8',
                        'format' => 'A4', 
                        'orientation' => 'L',
                        'margin_left' => '0',
                        'margin_right' => '0',
                        'margin_top' => '6',
                        'margin_bottom' => '6',
                        'padding_left' => '0',
                        'padding_right' => '0',
                        'padding_top' => '0',
                        'padding_bottom' => '0',
                        ]);

        $pdf->WriteHTML($html);

        $file = 'pdfCatalogue -'.time().'.pdf';

        if(request()->has('mode') && request()->mode == 'download') {
            return $pdf->Output($file, Destination::DOWNLOAD);
        }

            return $pdf->Output($file, Destination::DOWNLOAD);
        // return $pdf->Output($file, Destination::INLINE);
    }
    
    function pdfLabel($file, $model)
    {

        $options = settings()->getMany(['uploaded_logo']);
        $options = array_filter($options, function($key){
            return !is_null($key);
        } );
    
        // dd($options['header-html']);
        $html = view($file, ['model' => $model, 'options' => $options]);
        // $pdf = new Mpdf(config('pdf'));

        $pdf = new Mpdf(['mode' => 'utf-8',
                        'format' => 'A7', 
                        'orientation' => 'L',
                        'margin_left' => '0',
                        'margin_right' => '0',
                        'margin_top' => '0',
                        'margin_bottom' => '0',
                        'padding_left' => '0',
                        'padding_right' => '0',
                        'padding_top' => '0',
                        'padding_bottom' => '0',
                        ]);

        $pdf->WriteHTML($html);

        $file = $model->number.'-'.time().'.pdf';

        if(request()->has('mode') && request()->mode == 'download') {
            return $pdf->Output($file, Destination::DOWNLOAD);
        }

        return $pdf->Output($file, Destination::INLINE);
    }
}

function wasteLabel($file, $model)
    {

        $options = settings()->getMany(['uploaded_logo']);
        $options = array_filter($options, function($key){
            return !is_null($key);
        } );
    
        // dd($options['header-html']);
        $html = view($file, ['model' => $model, 'options' => $options]);
        // $pdf = new Mpdf(config('pdf'));

        $pdf = new Mpdf(['mode' => 'utf-8',
                        'format' => 'A7', 
                        'orientation' => 'L',
                        'margin_left' => '1',
                        'margin_right' => '1',
                        'margin_top' => '1',
                        'margin_bottom' => '1',
                        'padding_left' => '0',
                        'padding_right' => '0',
                        'padding_top' => '0',
                        'padding_bottom' => '0',
                        ]);

        $pdf->WriteHTML($html);
        
        // $pdf->SetWatermarkText('Propack');
        // $pdf->setWatermarkImage('http://192.168.16.193/images/logo.png');
        $pdf->showWatermarkText = false;
        $pdf->showWatermarkImage = false;
        $file = $model->number.'-'.time().'.pdf';

        if(request()->has('mode') && request()->mode == 'download') {
            return $pdf->Output($file, Destination::DOWNLOAD);
        }

        return $pdf->Output($file, Destination::INLINE);
    }

    function receiveLotsLabel($file, $model)
    {

        $options = settings()->getMany(['uploaded_logo']);
        $options = array_filter($options, function($key){
            return !is_null($key);
        } );
    
        // dd($options['header-html']);
        $html = view($file, ['model' => $model, 'options' => $options]);
        // $pdf = new Mpdf(config('pdf'));

        $pdf = new Mpdf(['mode' => 'utf-8',
                        'format' => 'A7', 
                        'orientation' => 'L',
                        'margin_left' => '1',
                        'margin_right' => '1',
                        'margin_top' => '1',
                        'margin_bottom' => '1',
                        'padding_left' => '0',
                        'padding_right' => '0',
                        'padding_top' => '0',
                        'padding_bottom' => '0',
                        ]);

        $pdf->WriteHTML($html);
        
        // $pdf->SetWatermarkText('Propack');
        // $pdf->setWatermarkImage('http://192.168.16.193/images/logo.png');
        $pdf->showWatermarkText = false;
        $pdf->showWatermarkImage = false;
        $file = time().'.pdf';

        if(request()->has('mode') && request()->mode == 'download') {
            return $pdf->Output($file, Destination::DOWNLOAD);
        }

        return $pdf->Output($file, Destination::INLINE);
    }

function pdfRaw($file, $model) {
    $options = settings()->getMany(['header-html', 'footer-html']);
    $options = array_filter($options, function($key){
        return !is_null($key);
    } );

    // dd($options['header-html']);
    $html = view($file, ['model' => $model, 'options' => $options]);
    $pdf = new Mpdf(config('pdf'));
    $pdf->WriteHTML($html);

    return $pdf;
}

function moneyFormat($value, $currency, $code = true)
{
    $amount = number_format($value, $currency->decimal_place);

    return $code? $currency->code.' '.$amount : $amount;
}


function selectedTax($items)
{
    $taxes = [];

    foreach($items as $item) {
        if($item->taxes && count($item->taxes) > 0) {
            foreach($item->taxes as $tax) {
                $key = $tax->name.' '.$tax->rate.'%';
                if(isset($taxes[$key])) {
                    $taxes[$key] = $taxes[$key] + ($item->unit_price * $item->qty) * $tax->rate / 100;
                } else {
                    $taxes[$key] = ($item->unit_price * $item->qty) * $tax->rate / 100;
                }
            }
        }
    }

    return $taxes;
}

function selectedTax2($items)
{
    $taxes = [];

    foreach($items as $item) {
        if($item->taxes && count($item->taxes) > 0) {
            foreach($item->taxes as $tax) {
                $key = $tax->name.' '.$tax->rate.'%';
                if(isset($taxes[$key])) {
                    $taxes[$key] = $taxes[$key] + ($item->price * $item->quantity) * $tax->rate / 100;
                } else {
                    $taxes[$key] = ($item->price * $item->quantity) * $tax->rate / 100;
                }
            }
        }
    }

    return $taxes;
}
