<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        $this->seedData();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }

    protected function seedData()
    {   
        
        $now = Carbon::today();
        // echo $now->year;
        // echo $now->month;
        // echo $now->weekOfYear;

        $settings = [
            'currency_id' => '1',
            'app_title' => 'Kabbouchi Mini ERP',
            'uploaded_logo' => null,
            'company_name' => 'Kabbouchi Mini ERP',
            'company_type' => 0,
            'company_address' => 'Country - Office - Bldg. - Main Road',
            'company_telephone' => '(+000) 00 123 456',
            'company_email' => 'kabbouchi_erp@outlook.com',
            'company_website' => 'www.perceiveagency.me',
            'company_payment_details' => null,
            'sent_from_email' => 'kabbouchi_erp@outlook.com',
            'sent_from_name' => 'perceiveagency Support',
            'global_bcc_email' => 'kabbouchi_erp@outlook.com',
            'footer_line_1' => 'Kabbouchi Mini ERP  Country - Office - Bldg  Main Road  Creative Development Agency',
            'footer_line_2' => 'kabbouchi_erp@outlook.com  www.perceiveagency.me  Tel.: +000 00 123 456    Fax: +000 00 123 456    MOF No. 234567-789',
            'footer_line_3' => '     .',
            'header' => '',
            'footer' => null,
            'header-html' => null,
            'footer-html' => null,
            'erp'=> '#ERP#2021#Perceive',
            'display_vat' => 0,
            'working_days' => 7,
            'starting_date' => $now->year,
            'disable_second_currency'=> 0,
            'invoices_available_qty'=> 0,
            'purchase_orders_email'=> 1,
            'purchase_orders_notification'=> 1,
            'invoices_email'=> 1,
            'invoices_notification'=> 1,
            'sales_orders_email'=> 1,
            'sales_orders_notification'=> 1,
            'quotations_email'=> 1,
            'quotations_notification'=> 1,
            'bills_email'=> 1,
            'bills_notification'=> 1,
            'app_color'=> 'rgb(136 194 65 / 80%)',
            'nav_color' => '#233c46',
            'text_color' => '#fff',
            'copyrights' => 'v2.2.2 perceiveagency.me © Copyright 2022. All Rights Reserved.',
            'license_email' => 'charbelkabbouchi@outlook.com',
            'box_1' => '1',
            'box_2' => '1',
            'box_3' => '1',
            'box_4' => '1',
            'box_5' => '0',
            'box_6' => '1',
            'box_7' => '1',
            'box_8' => '0',
            'box_9' => '1',
            'box_10' => '1',
            'box_11' => '1',
            'box_12' => '0',
            'box_13' => '1',
            'box_14' => '0',
            'box_15' => '0',
            'chart_1' => '1',
            'chart_2' => '1',
        ];

        $processed = [];
        $today = today();

        foreach($settings as $key => $value) {
            $processed[] = [
                'key' => $key,
                'value' => $value,
                'created_at' => $today,
                'updated_at' => $today
            ];
        }

        DB::table('settings')->insert($processed);
    }
}
