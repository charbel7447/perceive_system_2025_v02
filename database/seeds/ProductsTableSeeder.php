<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Product\Product;
use App\Product\Item;
use App\Product\Tax;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Product::truncate();
        Item::truncate();
        Tax::truncate();

        $products = [
            'Polyethylene – HDPE Film EE‐4811‐AAH',
            'Polyethylene – LLDPE Film EE‐1802‐BSB',
            'Polyester Chips RAMAPET S1',
            'Polyethylene – LLDPE Film EE‐1801‐BSBs',
            'Polyethylene – MDPE Roto Molding EM‐3405‐UVH',
            'POLYPROPYLENE PP H4120',
            'POLYPROPYLENE PP H1030'
        ];
        foreach($products as $i) {
            $product = new Product([
                'user_id' => 1,
                'code' => 'P'.$faker->unique()->numberBetween(1000000, 9000000),
                'description' => $i,
                'unit_price' => $faker->numberBetween(800, 1100),
                'currency_id' => 1,
                'has_inventory' => 1
            ]);

            $product->save();
            foreach(range(0, mt_rand(0, 7)) as $j) {
                Tax::create([
                    'product_id' => $product->id,
                    'name' => $faker->randomElement(['VAT 11']),
                    'rate' => $faker->randomElement([11]),
                    'tax_authority' => 'Tax Authority'
                ]);
            }
            foreach(range(1, mt_rand(1, 3)) as $j) {
                Item::create([
                    'product_id' => $product->id,
                    'vendor_id' => $faker->numberBetween(1, 100),
                    'reference' => $faker->numberBetween(2000000, 9000000),
                    'price' => $faker->numberBetween(5, 300),
                    'currency_id' => 1
                ]);
            }
        }
    }
}
