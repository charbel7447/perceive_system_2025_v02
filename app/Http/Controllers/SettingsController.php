<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Hash;
// use Artisan;
use App\FileUpload;
use DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class SettingsController extends Controller
{
    public function showPersonal()
    {
        $user = auth()->user();
        $form = [
            'name' => $user->name,
            'title' =>  $user->title,
            'mobile_number' => $user->mobile_number,
            'telephone' => $user->telephone,
            'extension' => $user->extension,
            'password' => null,
            'password_confirmation' => null,
            'email_signature' => $user->email_signature,
            'email' => $user->email,
            'company' => settings()->get('company_name')
        ];

        return api([
            'form' => $form
        ]);
    }

    public function storePersonal(Request $request)
    {
        $model = auth()->user();
        $request->validate([
            'name' => 'required|max:255',
            'title' => 'nullable|max:255',
            'mobile_number' => 'nullable|max:255',
            'telephone' => 'nullable|max:255',
            'extension' => 'nullable|max:255',
            'current_password' => 'sometimes|min:6|max:60',
            'new_password' => 'required_with:current_password|confirmed|min:6|max:60',
            'email_signature' => 'required|max:255'
        ]);

        if($request->has('current_password')) {
            if(Hash::check($request->current_password, $model->password)) {
                // match passwords
                $model->password = $request->new_password;
            } else {
                // throw error
                return api([
                    'errors' => [
                        'current_password' => ['Current password is invalid, Please try again with correct password!']
                    ]
                ], 422);
            }
        }

        $model->fill($request->except('password', 'email'));
        $model->save();

        return api([
            'saved' => true
        ]);
    }

    
    public function update_license()
    {
       DB::table('settings')->where('key','=','starting_date')->update(['value' => date('Y')]);
       return view('login');
    }

    public function clear_license()
    {
       DB::table('settings')->where('key','=','starting_date')->update(['value' => date('Y')-2]);
       return view('login');
    }

    public function clear_login_history()
    {
       DB::table('activity_log')->truncate();
       return view('login');
    }

    public function clear_log()
    {
       DB::table('cache_log')->truncate();
       DB::table('cart_log')->truncate();
       DB::table('invoice_items_log')->truncate();
       DB::table('invoices_log')->truncate();
       DB::table('client_payments_log')->truncate();
       DB::table('product_log')->truncate();
       DB::table('sales_orders_log')->truncate();
       DB::table('purchase_orders_log')->truncate();
       DB::table('purchase_order_items_log')->truncate();
       DB::table('quotations_log')->truncate();
       DB::table('quotation_log_items')->truncate();
       DB::table('sales_order_items_log')->truncate();
       DB::table('vendor_payments_log')->truncate();
       DB::table('bills_log')->truncate();
       DB::table('bill_items_log')->truncate();
        		
       return view('login');
    }
    
    public function reset_on_hold_qty()
    {
       $products = \App\Product\Product::get();
       foreach($products as  $product){
        \App\Product\Product::where('id','=', $product->id)->update(['on_hold_qty' => 0]);
       }
       return view('login');
    }

    public function reset_last_payment_date()
    {
       $clients = \App\Client::get();
       foreach($clients as  $client){
        \App\Client::where('id','=', $client->id)->update(['last_payment_date' => null]);
       }
       return view('login');
    }

    
    

    public function clear_db()
    {
        \App\ProductReport\ProductReport::truncate();
        \App\ProductReport\Item::truncate();
        return view('login');
    }
    
    public function show()
    {
        $currency = currency()->defaultToArray();

        $settings = settings()->getMany([
            'uploaded_logo', 'app_title','company_type','invoices_available_qty',
            'company_name', 'company_address',
            'company_telephone', 'company_email', 'company_website',
            'sent_from_email', 'sent_from_name', 'global_bcc_email',
            'footer_line_1', 'footer_line_2', 'footer_line_3',
            'header_line_1', 'header_line_2', 'header_line_3','extra_line',
            'quotation_field_1','quotation_field_2','quotation_field_3','quotation_field_4',
            'sales_order_field_1','sales_order_field_2','sales_order_field_3','sales_order_field_4',
            'invoice_field_1','invoice_field_2','invoice_field_3','invoice_field_4',
            'purchase_order_field_1','purchase_order_field_2','purchase_order_field_3','purchase_order_field_4',
            'bill_field_1','bill_field_2','bill_field_3','bill_field_4',
            'header', 'footer','display_vat','disable_second_currency',
            'purchase_orders_email',
            'purchase_orders_notification',
            'invoices_email',
            'invoices_notification',
            'sales_orders_email',
            'sales_orders_notification',
            'quotations_email',
            'quotations_notification',
            'bills_email',
            'bills_notification',
            'app_color','text_color','copyrights','license_email','nav_color',
            'box_1',
            'box_2',
            'box_3',
            'box_4',
            'box_5',
            'box_6',
            'box_7',
            'box_8',
            'box_9',
            'box_10',
            'box_11',
            'box_12',
            'box_13',
            'box_14',
            'box_15',
            'chart_1',
            'chart_2',
            'display_vat_rate',
            'display_exchange_rate',
            'product_dropdown_1',
            'product_dropdown_2',
            'client_dropdown_1',
            'client_dropdown_2',
            'global_vat_percentage'
        ]);

        

        $form = array_merge($currency, $settings);

        // throw new \Exception(config('app.version'));
        // 🔹 Add version info
        $currentVersion = config('app.version'); // e.g. 1.0.0
        $latestVersion  = $this->fetchLatestVersion(); // external check
        $isUpdateAvailable = version_compare($latestVersion, $currentVersion, '>');
        return api([
            'form' => $form,
            'version' => [
                'current' => $currentVersion,
                'latest' => $latestVersion,
                'is_update_available' => $isUpdateAvailable,
            ]
        ]);
    
    }



public function updateSystemLive()
{
    if (!auth()->user()->is_admin) {
        return response()->json([
            'saved' => false,
            'message' => 'Unauthorized.'
        ]);
    }

    $basePath = base_path();
    $steps = [
        'Pull Git' => "cd $basePath && git fetch origin main 2>&1 && git reset --hard origin/main 2>&1",
        'Build Frontend' => "cd $basePath && npm run dev 2>&1",
    ];

    return response()->stream(function () use ($steps, $basePath) {
        foreach ($steps as $stepName => $cmd) {
            echo json_encode(['step' => $stepName, 'line' => "=== STEP: $stepName ==="]) . "\n";
            flush();

            $process = proc_open(
                $cmd,
                [1 => ['pipe','r'], 2 => ['pipe','r']],
                $pipes
            );

            if (is_resource($process)) {
                while (!feof($pipes[1]) || !feof($pipes[2])) {
                    if ($line = fgets($pipes[1])) {
                        echo json_encode(['step' => $stepName, 'line' => $line]) . "\n";
                        flush();
                    }
                    if ($err = fgets($pipes[2])) {
                        echo json_encode(['step' => $stepName, 'line' => $err]) . "\n";
                        flush();
                    }
                }
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);
            }
        }

        // ✅ Update APP_VERSION
        $latestVersion = $this->fetchLatestVersion();
        $envPath = $basePath.'/.env';
        $envContent = File::get($envPath);
        $oldEnvVersion = '1.0.0'; // fallback
        if (preg_match('/^APP_VERSION=(.*)$/m', $envContent, $matches)) {
            $oldEnvVersion = trim($matches[1]);
        }

        if (preg_match('/^APP_VERSION=.*$/m', $envContent)) {
            $envContent = preg_replace('/^APP_VERSION=.*$/m', 'APP_VERSION='.$latestVersion, $envContent);
        } else {
            $envContent .= "\nAPP_VERSION=".$latestVersion;
        }

        File::put($envPath, $envContent);
        Artisan::call('config:clear');

        echo json_encode(['step' => 'Done', 'line' => "✅ System updated to version: $latestVersion"]) . "\n";

        echo json_encode(['step' => 'Done', 'line' => "✅ System updated to version: $oldEnvVersion. ' '.$latestVersion "]) . "\n";
        flush();

        // ✅ Conditional table creation & fetch
        if ($oldEnvVersion === '1.6.6' && $latestVersion === '1.6.7') {
            // Update test6
            DB::table('test6')->where('id', 1)->update(['body' => $latestVersion]);

            // Ensure DB connection is active
            DB::connection()->getPdo();

            // Create test7 if not exists
            if (!Schema::hasTable('test7')) {
                Schema::create('test7', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->integer('quantity')->default(0);
                    $table->timestamps();
                });

                echo json_encode(['step' => 'DB', 'line' => "✅ Table 'test7' created"]) . "\n";
                flush();
            }

            // Fetch data from test7
            $data = DB::table('test7')->get();
            echo json_encode(['step' => 'DB', 'line' => "Fetched ".count($data)." rows from 'test7'"]) . "\n";
            flush();
        }

    }, 200, ['Content-Type' => 'text/event-stream']);
}
private function fetchLatestVersion()
{
    $update_url = DB::table('settings')->where('key', 'update_url')->value('value');

    try {
        // fallback if DB is empty
        $url = $update_url ?: 'https://raw.githubusercontent.com/charbel7447/perceive_system_2025_v02/main/VERSION.txt';

        // Ensure URL is valid (prepend https if missing)
        if (!preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }

        $latest = @file_get_contents($url);

        return $latest ? trim($latest) : "1.0.0";
    } catch (\Exception $e) {
        \Log::error("Failed to fetch latest version: " . $e->getMessage());
        return "1.0.0";
    }
}



public function store(Request $request)
    {
        $request->validate([
            'currency_id' => 'required|integer|exists:currencies,id',
            'app_title' => 'nullable|max:255',
            'uploaded_logo_file' => 'nullable|image|max:2048',
            'company_name' => 'nullable|max:255',
            'company_type' => 'nullable|max:255',
            'company_address' => 'nullable|max:255',
            'company_telephone' => 'nullable|max:255',
            'company_email' => 'nullable|email',
            'company_website' => 'nullable|max:255',
            'company_payment_details' => 'nullable|max:255',
            'sent_from_email' => 'nullable|email',
            'sent_from_name' => 'nullable|max:255',
            'global_bcc_email' => 'nullable|email',
            'footer_line_1' => 'nullable|max:255',
            'footer_line_2' => 'nullable|max:255',
            'footer_line_3' => 'nullable|max:255',
            'header_line_1' => 'nullable|max:255',
            'header_line_2' => 'nullable|max:255',
            'header_line_3' => 'nullable|max:255',
            'quotation_field_1' => 'nullable|max:255',
            'quotation_field_2' => 'nullable|max:255',
            'quotation_field_3' => 'nullable|max:255',
            'quotation_field_4' => 'nullable|max:255',
            
            'sales_order_field_1' => 'nullable|max:255',
            'sales_order_field_2' => 'nullable|max:255',
            'sales_order_field_3' => 'nullable|max:255',
            'sales_order_field_4' => 'nullable|max:255',
            
            'invoice_field_1' => 'nullable|max:255',
            'invoice_field_2' => 'nullable|max:255',
            'invoice_field_3' => 'nullable|max:255',
            'invoice_field_4' => 'nullable|max:255',
            
            'purchase_order_field_1' => 'nullable|max:255',
            'purchase_order_field_2' => 'nullable|max:255',
            'purchase_order_field_3' => 'nullable|max:255',
            'purchase_order_field_4' => 'nullable|max:255',
            
            'bill_field_1' => 'nullable|max:255',
            'bill_field_2' => 'nullable|max:255',
            'bill_field_3' => 'nullable|max:255',
            'bill_field_4' => 'nullable|max:255',
            
            'extra_line' => 'nullable|max:255',
            'header_file' => 'nullable|image|max:2048',
            'footer_file' => 'nullable|image|max:2048',
            'display_vat' => 'nullable|max:255',
            'disable_second_currency'=> 'nullable|max:255',
            'file' => 'nullable',
            'invoices_available_qty'=> 'nullable',
            'app_color'=> 'nullable',
            'purchase_orders_email'=> 'nullable',
            'purchase_orders_notification'=> 'nullable',
            'invoices_email'=> 'nullable',
            'invoices_notification'=> 'nullable',
            'sales_orders_email'=> 'nullable',
            'sales_orders_notification'=> 'nullable',
            'quotations_email'=> 'nullable',
            'quotations_notification'=> 'nullable',
            'bills_email'=> 'nullable',
            'bills_notification'=> 'nullable',
            'text_color'=> 'nullable',
            'copyrights' => 'nullable',
            'license_email' => 'nullable',
            'nav_color' => 'nullable',
            'box_1' => 'nullable',
            'box_2' => 'nullable',
            'box_3' => 'nullable',
            'box_4' => 'nullable',
            'box_5' => 'nullable',
            'box_6' => 'nullable',
            'box_7' => 'nullable',
            'box_8' => 'nullable',
            'box_9' => 'nullable',
            'box_10' => 'nullable',
            'box_11' => 'nullable',
            'box_12' => 'nullable',
            'box_13' => 'nullable',
            'box_14'=> 'nullable',
            'box_15'=> 'nullable',
            'chart_1' => 'nullable',
            'chart_2' => 'nullable',
            'display_vat_rate'=> 'nullable',
            'display_exchange_rate'=> 'nullable',
            'product_dropdown_1'=> 'nullable',
            'product_dropdown_2'=> 'nullable',
            'client_dropdown_1'=> 'nullable',
            'client_dropdown_2'=> 'nullable',
            'global_vat_percentage' => 'nullable'
        ]);

        // upload document if exists
        $this->uploadIfExist('uploaded_logo', 'uploaded_logo_file');
        
        $header = $this->uploadIfExist('header', 'header_file');

        if($header) {
            // generate html
            $headerHtmlPath = $this->createHTMLfile($header, 'header');
            settings()->set('header-html', $headerHtmlPath);
        }

        $footer = $this->uploadIfExist('footer', 'footer_file');

        if($footer) {
            // generate html
            $footerHtmlPath = $this->createHTMLfile($footer, 'footer');
            settings()->set('footer-html', $footerHtmlPath);
        }

        settings()->setMany($request->only([
            'currency_id', 'app_title','company_type',
            'company_name', 'company_address', 'company_telephone',
            'company_email','company_website','invoices_available_qty',
            'company_payment_details','app_color',
            'sent_from_email', 'sent_from_name','global_bcc_email',
            'footer_line_1', 'footer_line_2', 'footer_line_3', 'display_vat','disable_second_currency',
            'header_line_1', 'header_line_2', 'header_line_3','extra_line',
            'quotation_field_1','quotation_field_2','quotation_field_3','quotation_field_4',
            'sales_order_field_1','sales_order_field_2','sales_order_field_3','sales_order_field_4',
            'invoice_field_1','invoice_field_2','invoice_field_3','invoice_field_4',
            'purchase_order_field_1','purchase_order_field_2','purchase_order_field_3','purchase_order_field_4',
            'bill_field_1','bill_field_2','bill_field_3','bill_field_4',
            'purchase_orders_email',
            'purchase_orders_notification',
            'invoices_email',
            'invoices_notification',
            'sales_orders_email',
            'sales_orders_notification',
            'quotations_email',
            'quotations_notification',
            'bills_email',
            'bills_notification',
            'text_color',
            'copyrights','license_email','nav_color',
            'box_1',
            'box_2',
            'box_3',
            'box_4',
            'box_5',
            'box_6',
            'box_7',
            'box_8',
            'box_9',
            'box_10',
            'box_11',
            'box_12',
            'box_13',
            'box_14',
            'box_15',
            'chart_1',
            'chart_2',
            'display_vat_rate',
            'display_exchange_rate',
             'product_dropdown_1',
            'product_dropdown_2',
             'client_dropdown_1',
            'client_dropdown_2',
            'global_vat_percentage'
        ]));

        return api([
            'saved' => true
        ]);
    }

    

    protected function uploadIfExist($settings, $file)
    {
        if(request()->hasFile($file) && request()->file($file)->isValid()) {
            // store in public uploads folder by default
           if($fileName = uploadFile(request()->file($file))) {
                // overwrite previous uploaded file
                deleteFile(settings()->get($settings));
                settings()->set($settings, $fileName);

                return $fileName;
           }
           if($fileName = uploadLogo(request()->file($file))) {
            // overwrite previous uploaded file
            deleteFile(settings()->get($settings));
            settings()->set($settings, $fileName);

            return $fileName;
       }
        }
    }


    public function npm (Request $request)
    {
       dd(shell_exec('npm run production'));
    }
   
    public function migrate (Request $request)
    {
        \Artisan\Artisan::call('artisan_command',
        array(
            'migrate:fresh' => ['--seed' => true]));
    }

    public function upgrade (Request $request)
    {
        
        
    }
    


    protected function createHTMLfile($file, $type)
    {
        $path = storage_path('app/uploads/').$file;
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $data = File::get($path);
        $base64 = 'data:image/' .$extension. ';base64,' . base64_encode($data);

        $h ='<div>';
        $h ='    <img style="width: 110px;margin-top:-40px;float:left;padding:10px;z-index:1" src="'.$base64.'">';
        $h .='</div>';

        $path = storage_path('app/'.$type.'.html');

        File::put($path, $h);

        return $path;
    }
}
