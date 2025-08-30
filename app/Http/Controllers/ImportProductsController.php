<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use DB;
use Illuminate\Support\Facades\Hash;

class ImportProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.import_tools');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    public function products_import(Request $request) 
    {
        // Product::truncate();

       $collections = (new FastExcel)->import($request->file('file'));
      
        $data = [];

        foreach ($collections as $collection) {
            $id = $collection['id'];
            if($id){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['id' => $id]);

                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['slug' => $id]);

                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['code' => $id]);
            }

            $title = $collection['title'];
            if($title){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['title' => $title]);

                DB::table('products')
                    ->where('id','=',$collection['id'])
                    ->updateOrInsert([
                        'id' => $collection['id']
                        ],['summary' => $title]);

                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['description' => $title]);

                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['name' => $title]);

                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['tag' => $title]);

                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['details' => $title]);

                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['meta_title' => $title]);
                    
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['meta_description' => $title]);
            }

            $category_id = $collection['category_id'];
            if($category_id){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['category_id' => $category_id]);
            }

            $sub_categoryid = $collection['sub_categoryid'];
            if($sub_categoryid){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['sub_categoryid' => $sub_categoryid]);

                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['sub_category_id' => '["'.$sub_categoryid.'"]']);
            }

            $sub_sub_categoryid = $collection['sub_sub_categoryid'];
            if($sub_sub_categoryid){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['sub_sub_categoryid' => $sub_sub_categoryid]);

                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['sub_sub_category_id' => '["'.$sub_sub_categoryid.'"]']);
            }

            $image = $collection['image'];
            if($image){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['image' => $image]);

                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['product_image_gallery' => '["'.$image.'"]']);
            }

            $price = $collection['price'];
            if($price > 0){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['price' => $price]);

                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['original_price' => $price]);

                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['purchase_price' => $price]);

                   
    
            }
            $cost_price = $collection['cost_price'];
            if($cost_price > 0){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['sale_price' => $cost_price]);
            }

            $product_rating = $collection['product_rating'];
            if($product_rating > 0){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['product_rating' => $product_rating]);
            }

            $profit = $collection['profit'];
            if($profit > 0){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['rating_value' => $profit]);
            }

            $status = $collection['status'];
            if($status == 'disabled'){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['status' => 'disabled']);
            }
            if($status == 'publish'){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['status' => 'publish']);
            }
            
            $new = $collection['new'];
            if($new > 0){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['new' => $new]);
            }else{
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['new' => 0]);
            }
   
            
            $featured = $collection['featured'];
            if($featured > 0){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['featured' => $featured]);
            }else{
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['featured' => 0]);
            }
            
            $best_selling = $collection['best_selling'];
            if($best_selling > 0){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['best_selling' => $best_selling]);
            }else{
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['best_selling' => 0]);
            }
            
            $deal_of_the_day = $collection['deal_of_the_day'];
            if($deal_of_the_day > 0){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['deal_of_the_day' => $deal_of_the_day]);
            }else{
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['deal_of_the_day' => 0]);
            }
            

            // $ct_box = $collection['ct_box'];
            
            // $unitprice = $collection['unitprice'];
            // $sale_price = $collection['sale_price'];
            

            // if($sale_price && $ct_box){
            //     $unitPriceX = number_format($sale_price/$ct_box,2);
            //     DB::table('products')
            //     ->where('id','=',$collection['id'])
            //     ->updateOrInsert([
            //         'id' => $collection['id']
            //         ],['unitprice' => $unitPriceX ?? 0]);
            // }

            $unitprice = $collection['unitprice'];
            if($unitprice){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['unitprice' => $unitprice]);
            }
            
            $sale_price = $collection['cost_price'];
            $special_price = $collection['special_price'];
            if($special_price){
              
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['special_price' => $special_price]);
            }
            if($price){
              
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['unit_price' => $price]);
            }

            $category_ids = $collection['category_ids'];
            if($category_ids){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['category_ids' => $category_ids]);
            }

            $thumbnail = $collection['thumbnail'];
            if($thumbnail){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['thumbnail' => $thumbnail]);
                    
                  
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['images' => '["'.$thumbnail.'"]']);
            }

            $current_stock = $collection['current_stock'];
            if($current_stock){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['current_stock' => $current_stock]);
            }

            $product_rating = $collection['product_rating'];
            if($product_rating){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['product_rating' => $product_rating]);
            }
            
            $rating_value = $collection['profit'];
            if($rating_value){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['rating_value' => $rating_value]);
            }

            $rating_value = $collection['profit'];
            if($rating_value){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['rating_value' => $rating_value]);
            }

            $volume_box = $collection['volume_box'];
            if($volume_box){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['volume_box' => $volume_box]);
            }

            $ct_box = $collection['ct_box'];
            if($ct_box){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['ct_box' => $ct_box]);

                    DB::table('products')
                    ->where('id','=',$collection['id'])
                    ->updateOrInsert([
                        'id' => $collection['id']
                        ],['item_box' => $ct_box]);
            }

            $weight_box = $collection['weight_box'];
            if($weight_box){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['weight_box' => $weight_box]);
            }

            
            $nb_boxes_1 = $collection['nb_boxes_1'];
            if($nb_boxes_1){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['nb_boxes_1' => $nb_boxes_1]);
            }

            $nb_boxes_1_price = $collection['nb_boxes_1_price'];
            if($nb_boxes_1_price){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['nb_boxes_1_price' => $nb_boxes_1_price]);
            }

            $nb_boxes_2 = $collection['nb_boxes_2'];
            if($nb_boxes_2){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['nb_boxes_2' => $nb_boxes_2]);
            }

            $nb_boxes_2_price = $collection['nb_boxes_2_price'];
            if($nb_boxes_2_price){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['nb_boxes_2_price' => $nb_boxes_2_price]);
            }

            $nb_boxes_3 = $collection['nb_boxes_3'];
            if($nb_boxes_3){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['nb_boxes_3' => $nb_boxes_3]);
            }

            $nb_boxes_3_price = $collection['nb_boxes_3_price'];
            if($nb_boxes_3_price){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['nb_boxes_3_price' => $nb_boxes_3_price]);
            }

            $size = $collection['size'];
            if($size){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['size' => $size]);
            }

            $location = $collection['location'];
            if($location){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['location' => $location]);
            }
            
            $class_a_price = $collection['class_a_price'];
            if($class_a_price){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['class_a_price' => $class_a_price]);
            }
                        
            $class_b_price = $collection['class_b_price'];
            if($class_b_price){
                DB::table('percency_bryz_project_web_v3.products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['class_b_price' => $class_b_price]);
            }
                                    
            $class_c_price = $collection['class_c_price'];
            if($class_c_price){
                DB::table('products')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['class_c_price' => $class_c_price]);
            }

            DB::table('products')
            ->updateOrInsert([
            'id' => $collection['id']
            ],[
            'tax_percentage'  => 0,
            'uom'  => 'lb',
            'unit'  => 'lb',
            'badge'  => null,
            // 'status'  => 'publish',
            'attributes'  => "[]",
            'sold_count'  => 0,
            'deleted_at'  => NULL,
            'created_at'  => now(),
            'updated_at'  => now(),
            'added_by'  => 'seller',
            'user_id'  => 1,
            'brand_id'  => 0,
            'product_type'  => 'physical',
            // 'new'  => 0,
            // 'featured'  => 0,
            // 'best_selling'  => 0,
            // 'deal_of_the_day'  => 0,
            'deal_date'  => null,
            'discount'  => 0,
            'discount_type'  => 'flat',
            'minimum_order_qty'  => 1,
            'free_shipping'  => 0,
            'colors'  => "[]",
            'featured_status'  =>1,
            'request_status'  => 1,
            'published'  => 1,
            'variation'  => "[]",
            'choice_options'  => "[]",
            'refundable'  => 1,
            'min_qty'  => 1,
            'meta_image'  => "def.png",
            'color_image'  => "[]",
            'on_hold_qty'  => 0,
            'multiply_qty'  => 0,
            'special_image' => 0,
            'warehouse_id' => 1,
            'currency_id' => 1,
            'updated_by' => 'Admin',
            'created_by' => 'Admin',
            'uom_id' => 1,
            'minimum_stock' => 1,
            'unit'  => 'lb',
            'shipping_cost'  => 0,
            'tax'  => 0,
            'tax_type'  => 'percent',
            'tax_model'  => 'exclude',
          ]);
        
        
       

        $current_stock = $collection['current_stock'];
        if($current_stock){
            DB::table('product_inventories')
            ->where('product_id','=',$collection['id'])
            ->updateOrInsert([
                'product_id' => $collection['id']
                ],['stock_count' => $current_stock]);
        }else{
               DB::table('product_inventories')
          ->updateOrInsert([
          'product_id' => $collection['id']
          ],[
          'sku'  => $collection['id'],
          'stock_count'  => 0,
          'sold_count'  => 0,
          'created_at'  => now(),
          'updated_at'  => now()
        ]);
        }


        }
        return back();
    }


    
    public function clients_import(Request $request)
    {
        // Product::truncate();

       $collections = (new FastExcel)->import($request->file('file'));
      
        $data = [];
        $datax = [];
        // $skip = ['youtube_video_url', 'details', 'thumbnail'];
   
    
        foreach ($collections as $collection) {
            $datax[] = $collection['code'];
            $ref_number = $collection['ref_number'];
            if($ref_number){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['ref_number' => $ref_number]);
                
                
            }
            $name = $collection['name'];
            if($name){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['name' => $name]);
                    
                 DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['person' => $name]);

            }
            $email = $collection['email'];
            if($email){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['email' => $email]);

            }
            $username = $collection['username'];
            if($username){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['username' => $username]);
            }
            $seller_id = $collection['seller_id'];
            if($seller_id){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['seller_id' => $seller_id]);
                    
        
            }
          
            $phone = $collection['phone'];
            if($phone){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['phone' => $phone]);
                
         
            }
            $address = $collection['address'];
            if($address){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['address' => $address]);
                
           
            }
            $state = $collection['state'];
            if($state){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['state' => $state]);
            }
            $city = $collection['city'];
            if($city){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['city' => $city]);

            }
            $zipcode = $collection['zipcode'];
            if($zipcode){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['zipcode' => $zipcode]);

                
            }
            $company = $collection['company'];
            if($company){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['company' => $company]);
            }
            $country = $collection['country'];
            if($country){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['country' => $country]);

            }
            $password = $collection['password'];
            if($password){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['password' => Hash::make($password)]);
            }
            $allow_mobile = $collection['allow_mobile'];
            if($allow_mobile){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['allow_mobile' => $allow_mobile]);

            }
           
            $tax_id = $collection['tax_id'];
            if($tax_id){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['tax_id' => $tax_id]);
            }
            
            $balance = $collection['balance'];
            if($balance){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['balance' => $balance]);
            }
            
            $price_class = $collection['price_class'];
            if($price_class){
                DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['price_class' => $price_class]);

          
            }    
            DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['is_customer' => 1]);
            DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['balance_status' => 1]);
            DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['currency_id' => 1]);

            DB::table('users')
            ->where('id','=',$collection['code'])
            ->updateOrInsert([
                'id' => $collection['code']
                ],['email_verified' => now()]);
             
            DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['user_id' => 1]);

            DB::table('users')
            ->where('id','=',$collection['code'])
            ->updateOrInsert([
                'id' => $collection['code']
                ],['image' => '2023-05-10-645b9ac58a095.png']);

            DB::table('users')
                ->where('id','=',$collection['code'])
                ->updateOrInsert([
                    'id' => $collection['code']
                    ],['to_be_paid' => 0]);
            
            DB::table('users')
                    ->where('id','=',$collection['code'])
                    ->updateOrInsert([
                        'id' => $collection['code']
                        ],['paid' => 0]);


        }
        //   dd($datax);

        return back();
    }
    
    
    public function products_import2(Request $request) 
    {
        // Product::truncate();

       $collections = (new FastExcel)->import($request->file('file'));
      
        $data = [];

        foreach ($collections as $collection) {
            $id = $collection['id'];
            if($id){
                DB::table('products_2')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['id' => $id]);

                DB::table('products_2')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['code' => $id]);

            }
        }

           
        return back();
    }
    
    
    public function products_import_images(Request $request) 
    {
        // Product::truncate();

       $collections = (new FastExcel)->import($request->file('file'));
      
        $data = [];

        foreach ($collections as $collection) {
            $id = $collection['id'];
              $title = $collection['title'];
                $path = $collection['path'];
            if($id){
             
            
                DB::table('media_uploads')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['id' => $id]);

                DB::table('media_uploads')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['title' => $title]);
                    
                DB::table('media_uploads')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['path' => $path]);
                    
                DB::table('media_uploads')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['dimensions' => '334 x 333 pixels']);
                
                DB::table('media_uploads')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['created_at' => now()]);
                    
                
                DB::table('media_uploads')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['updated_at' => now()]);
            }
        }

           
        return back();
    }
    
    public function client_balances_import(Request $request) 
    {
        // Product::truncate();

       $collections = (new FastExcel)->import($request->file('file'));
      
        $data = [];

        foreach ($collections as $collection) {
            $client_id = $collection['client_id'];
            $balance = $collection['balance'];
            if($client_id){
                DB::table('invoices_2')
                ->where('client_id','=',$collection['client_id'])
                ->updateOrInsert([
                    'client_id' => $collection['client_id']
                    ],['client_id' => $client_id]);

                DB::table('invoices_2')
                ->where('client_id','=',$collection['client_id'])
                ->updateOrInsert([
                    'client_id' => $collection['client_id']
                    ],['balance' => $balance]);

            }
        }

           
        return back();
    }
    
    
    public function suppliers_import(Request $request) 
    {
        // Product::truncate();

       $collections = (new FastExcel)->import($request->file('file'));
      
        $data = [];

        foreach ($collections as $collection) {
            $id = $collection['id'];
            $name = $collection['name'];
            $contact = $collection['contact'];
            $phone = $collection['phone'];
            $address1 = $collection['address1'];
            $address2 = $collection['address2'];
            if($id){
             
                DB::table('vendors')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['id' => $id]);

                DB::table('vendors')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['person' => $contact]);
                    
                DB::table('vendors')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['company' => $name]);
                    
                DB::table('vendors')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['work_phone' => $phone]);
                
                DB::table('vendors')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['billing_address' => $address1]);
                    
                
                DB::table('vendors')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['shipping_address' => $address2]);
                
                DB::table('vendors')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['user_id' => 1]);    
                    
                    DB::table('vendors')
                ->where('id','=',$collection['id'])
                ->updateOrInsert([
                    'id' => $collection['id']
                    ],['currency_id' => 1]); 
                    
            }
        }

           
        return back();
    }
    
     public function suppliers_items(Request $request) 
    {
        // Product::truncate();

       $collections = (new FastExcel)->import($request->file('file'));
      
        $data = [];
        \App\Product\Item::truncate();
        foreach ($collections as $collection) {
            $product_id = $collection['product_id'];
            $vendor_id = $collection['vendor_id'];
      
            if($product_id){
                $product_price = DB::table('products')->where('id','=',$product_id)->value('sale_price');
                
                $vend_item = new \App\Product\Item;
                $vend_item->product_id = $product_id;
                $vend_item->vendor_id = $vendor_id;
                $vend_item->reference = 'reference';
                $vend_item->price = $product_price;
                $vend_item->currency_id = 1;
                $vend_item->save();
                    
            }
        }

           
        return back();
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
