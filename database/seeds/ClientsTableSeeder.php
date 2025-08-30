<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Client;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Client::truncate();

       
            $client1 = new Client([
                'user_id' => 1,
                'person' => 'Mr. Nasr Chidiac',
                'company' => 'Equiplast SARL',
                'email' =>  'nasr.chidiac@equiplast.lb.com',
                'work_phone' =>  '+961 9 448980',
                'mobile_number' => '11378-601',
                'vat_status' => '1',
                'currency_id' => 1,
                'billing_address' =>  'Address: Nahr Ibrahim, Imm Joseph Shehade',
                'shipping_address' =>  'Address: Nahr Ibrahim, Imm Joseph Shehade',
                'total_revenue' => 0
            ]);

            $client1->save();

            $client2 = new Client([
                'user_id' => 2,
                'person' => 'Mr. Sami Daher',
                'company' => 'Sadapack Industries S.A.L.',
                'email' =>  'info@sadapack.com',
                'work_phone' =>  '+961 7 986 000',
                'mobile_number' => '254539-601',
                'vat_status' => '1',
                'currency_id' => 1,
                'billing_address' =>  'Address: Jiyeh, Daher Al  . Mghara',
                'shipping_address' =>  'Address: Jiyeh, Daher Al  . Mghara',
                'total_revenue' => 0
            ]);

            $client2->save();

            $client3 = new Client([
                'user_id' => 3,
                'person' => 'Mr. Amin Kekhia',
                'company' => 'Industrial Plastic CO. SAL ( IPCO )',
                'email' =>  'h.kekhia@ipco.com.lb',
                'work_phone' =>  '+961 1 497312',
                'mobile_number' => '11948-601',
                'vat_status' => '1',
                'currency_id' => 1,
                'billing_address' =>  'Address: Industrial City, Sedd el Baouchrieh - Beirut',
                'shipping_address' =>  'Address: Industrial City, Sedd el Baouchrieh - Beirut',
                'total_revenue' => 0
            ]);

            $client3->save();

          
    }
}
