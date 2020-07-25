<?php

namespace App\Imports;

use App\Attribute;
use App\Category;
use App\Personalisationtype;
use App\Product;
use App\ProductPersonalisationtype;
use App\Purchaseprice;
use App\Quantity;
use App\Setting;
use App\Traits\CategoryMarkups;
use App\Traits\InsertMinMax;
use App\UsbPurchasePrice;
use App\UsbType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class ProductsImport implements ToModel, WithValidation, WithHeadingRow
{
    use InsertMinMax, CategoryMarkups;

    // For Counting Inserted Products
    private $total_rows = 0;

    // For Listing Main Categories
    protected $main_category_array = [];

    // For Listing Sub Categories
    protected $sub_categories_of_main_category_array = [];

    // For Listing Sub Sub Categories
    protected $sub_sub_categories_of_sub_category_array = [];

    /**
     * @param array $row
     * @return Model|Model[]|null
     */
    public function model(array $row)
    {
        // Initializing variables
        $quantity_list = $quantity_min_max_list = [];

        // Incrementing Total Row
        ++$this->total_rows;

        // Checking If The Manufacturer Directory Exist else Create The Directory With Manufacturer Name Ex: logo-line-australia
        $manufacturer_images_path = public_path('files/23/Photos/Products/' . seoUrl($row['manufacturer_name']));
        if (!File::isDirectory($manufacturer_images_path)) {
            File::makeDirectory($manufacturer_images_path, 0777, true, true);
        }

        // Getting Common Product's Information
        $site_data = Setting::select('delivery_charges', 'payment_terms', 'return_policy', 'disclaimer')
            ->get()
            ->first();

        // Getting The Quantity Title, Id Group By Title and Converting Into Array
        $quantity_name_array = Quantity::get(['id', 'title'])
            ->groupBy('title')
            ->toArray();

        // Getting Personalisation Type Name and ID grouping by Name and Converting to array
        $personalisation_type_name_array = Personalisationtype::get(['id', 'name'])
            ->groupBy('name')
            ->toArray();

        if (file_exists(public_path('files/23/Photos/Products/') . '/' . seoUrl($row['manufacturer_name']) . '/' . $row['main_image'])) {
            $main_image = $row['main_image'];
        } else {
            $main_image = "no_image.png";
        }

        if (isset($row['alternative_image']) && file_exists(public_path('files/23/Photos/Products/') . '/' . seoUrl($row['manufacturer_name']) . '/' . $row['alternative_image'])) {
            $alt_image = $row['alternative_image'];
        } else {
            $alt_image = "no_image.png";
        }

        // Creating Products
        $product = Product::create([
            'name' => trim($row['name']),
            'status' => $row['status'] ? trim($row['status']) : "0",
            'discontinued_stock' => $row['discontinued_stock'] ? trim($row['discontinued_stock']) : "0",
            'product_type' => trim($row['product_type']),
            'product_code' => trim($row['product_code']),
            'dimensions' => isset($row['dimensions']) ? trim($row['dimensions']) : null,
            'video_link' => isset($row['video_link']) ? trim($row['video_link']) : null,
            'print_area' => trim($row['print_area']),
            'decoration_areas' => trim($row['decoration_areas']),
            'main_image' => trim($main_image),
            'alternative_image' => trim($alt_image),
            'long_desc' => trim($row['product_description']),
            'short_desc' => shortenDescription(trim($row['product_description']), 15),
            'product_features' => trim($row['product_features']),
            'product_item_size' => trim($row['product_item_size']),
            'delivery_charges' => $row['delivery_charges'] ? trim($row['delivery_charges']) : $site_data->delivery_charges,
            'payment_terms' => $row['payment_terms'] ? trim($row['payment_terms']) : $site_data->payment_terms,
            'return_policy' => $row['return_policy'] ? trim($row['return_policy']) : $site_data->return_policy,
            'disclaimer' => $row['disclaimer'] ? trim($row['disclaimer']) : $site_data->disclaimer,
            'manufacturer_id' => get_manufacturer_id_by_name(trim($row['manufacturer_name'])),
            'manufacturer_key' => seoUrl(trim($row['manufacturer_name'])),
        ]);

        // Creating Purchase Price For Promo Products
        if ($row['product_type'] && $row['product_type'] == "promo_product") {

            // Creating Quantity Wise Purchase Price For The Product
            $this->createPurchasePrice($row, $product, $quantity_name_array);
        } elseif ($row['product_type'] && $row['product_type'] == "usb_product") {

            // Getting The USB Type Titles, Id and Group By Title and Converting Into Array
            $usb_type_titles = UsbType::get(['id', 'title'])
                ->groupBy('title')
                ->toArray();

            // Creating Quantity and GB Wise Purchase Price For The Product
            $this->createUsbPurchasePrice($row, $product, $quantity_name_array, $usb_type_titles);
        }

        // Creating Product Category
        $this->createProductCategory($row, $product);

        // Saving The Min Max Price For A Product
        if (count($product->purchasePrices) || count($product->usbPurchasePrices)) {

            // Creating Quantity Id List
            $quantity_list = $this->createQuantityIdList($product->toArray(), $quantity_list);

            $category_id = isset($this->sub_sub_categories_of_sub_category_array[$row['sub_sub_category']]['id']) ? $this->sub_sub_categories_of_sub_category_array[$row['sub_sub_category']]['id'] : null;

            $product_type = $product->product_type;

            // getCategoryMarkups method returns
            // [quantity_titles, category_markups, usb_type_titles & quantity_min_max_list]
            $get_category_markups = $this->getCategoryMarkups(
                $product->toArray(),
                $product_type,
                $category_id,
                $quantity_list,
                $quantity_min_max_list
            );

            // Inserting Min Max Price and Quantity To The Products Table
            $this->insertMinMaxPriceQuantity($product, $get_category_markups);
        }

        // Creating Product Personalisation Types
        $this->createProductPersonalisationType($row, $product, $personalisation_type_name_array);

        // Creating Product Attribute
        $this->createProductAttribute($row, $product);

        return $product;
    }

    /**
     * @param array $row
     * @param $product
     * @param $quantity_name_array
     */
    public function createPurchasePrice(array $row, $product, $quantity_name_array): void
    {
        // Creating Product Purchase Prices For Quantity 50
        if ($row['price_50'] && isset($quantity_name_array['50+'][0]['id'])) {
            Purchaseprice::create([
                'product_id' => $product->id,
                'qty_id' => $quantity_name_array['50+'][0]['id'],
                'price' => $row['price_50'] != "CALL" ? trim($row['price_50']) : 0
            ]);
        }

        // Creating Product Purchase Prices For Quantity 100
        if ($row['price_100'] && isset($quantity_name_array['100+'][0]['id'])) {
            Purchaseprice::create([
                'product_id' => $product->id,
                'qty_id' => $quantity_name_array['100+'][0]['id'],
                'price' => $row['price_100'] != "CALL" ? trim($row['price_100']) : 0
            ]);
        }

        // Creating Product Purchase Prices For Quantity 250
        if ($row['price_250'] && isset($quantity_name_array['250+'][0]['id'])) {
            Purchaseprice::create([
                'product_id' => $product->id,
                'qty_id' => $quantity_name_array['250+'][0]['id'],
                'price' => $row['price_250'] != "CALL" ? trim($row['price_250']) : 0
            ]);
        }

        // Creating Product Purchase Prices For Quantity 500
        if ($row['price_500'] && isset($quantity_name_array['500+'][0]['id'])) {
            Purchaseprice::create([
                'product_id' => $product->id,
                'qty_id' => $quantity_name_array['500+'][0]['id'],
                'price' => $row['price_500'] != "CALL" ? trim($row['price_500']) : 0
            ]);
        }

        // Creating Product Purchase Prices For Quantity 1000
        if ($row['price_1000'] && isset($quantity_name_array['1000+'][0]['id'])) {
            Purchaseprice::create([
                'product_id' => $product->id,
                'qty_id' => $quantity_name_array['1000+'][0]['id'],
                'price' => $row['price_1000'] != "CALL" ? trim($row['price_1000']) : 0
            ]);
        }

        // Creating Product Purchase Prices For Quantity 2500
        if ($row['price_2500'] && isset($quantity_name_array['2500+'][0]['id'])) {
            Purchaseprice::create([
                'product_id' => $product->id,
                'qty_id' => $quantity_name_array['2500+'][0]['id'],
                'price' => $row['price_2500'] != "CALL" ? trim($row['price_2500']) : 0
            ]);
        }

        // Creating Product Purchase Prices For Quantity 5000
        if ($row['price_5000'] && isset($quantity_name_array['5000+'][0]['id'])) {
            Purchaseprice::create([
                'product_id' => $product->id,
                'qty_id' => $quantity_name_array['5000+'][0]['id'],
                'price' => $row['price_5000'] != "CALL" ? trim($row['price_5000']) : 0
            ]);
        }

        // Creating Product Purchase Prices For Quantity 10000
        if ($row['price_10000'] && isset($quantity_name_array['10000+'][0]['id'])) {
            Purchaseprice::create([
                'product_id' => $product->id,
                'qty_id' => $quantity_name_array['10000+'][0]['id'],
                'price' => $row['price_10000'] != "CALL" ? trim($row['price_10000']) : 0
            ]);
        }

        // Creating Product Purchase Prices For Quantity 10000
        if ($row['price_50000'] && isset($quantity_name_array['50000+'][0]['id'])) {
            Purchaseprice::create([
                'product_id' => $product->id,
                'qty_id' => $quantity_name_array['50000+'][0]['id'],
                'price' => $row['price_50000'] != "CALL" ? trim($row['price_50000']) : 0
            ]);
        }
    }


    /**
     * @param array $row
     * @param $product
     * @param $quantity_name_array
     * @param $usb_type_titles
     */
    public function createUsbPurchasePrice(array $row, $product, $quantity_name_array, $usb_type_titles): void
    {
        $one_gb = isset($usb_type_titles['1 GB'][0]['id']) ? $usb_type_titles['1 GB'][0]['id'] : null;
        $two_gb = isset($usb_type_titles['2 GB'][0]['id']) ? $usb_type_titles['2 GB'][0]['id'] : null;
        $four_gb = isset($usb_type_titles['4 GB'][0]['id']) ? $usb_type_titles['4 GB'][0]['id'] : null;
        $eight_gb = isset($usb_type_titles['8 GB'][0]['id']) ? $usb_type_titles['8 GB'][0]['id'] : null;
        $sixteen_gb = isset($usb_type_titles['16 GB'][0]['id']) ? $usb_type_titles['16 GB'][0]['id'] : null;
        $thirty_two_gb = isset($usb_type_titles['32 GB'][0]['id']) ? $usb_type_titles['32 GB'][0]['id'] : null;
        $sixty_four_gb = isset($usb_type_titles['64 GB'][0]['id']) ? $usb_type_titles['64 GB'][0]['id'] : null;
        $one_twenty_eight_gb = isset($usb_type_titles['128 GB'][0]['id']) ? $usb_type_titles['128 GB'][0]['id'] : null;

        if (isset($one_gb)) {
            // Creating USB Product Purchase Prices For Quantity 50 and 1gb
            if ($row['1gb_price_50'] && isset($quantity_name_array['50+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50+'][0]['id'],
                    'price' => $row['1gb_price_50'] != "CALL" ? trim($row['1gb_price_50']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 100 and 1gb
            if ($row['1gb_price_100'] && isset($quantity_name_array['100+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['100+'][0]['id'],
                    'price' => $row['1gb_price_100'] != "CALL" ? trim($row['1gb_price_100']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 250 and 1gb
            if ($row['1gb_price_250'] && isset($quantity_name_array['250+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['250+'][0]['id'],
                    'price' => $row['1gb_price_250'] != "CALL" ? trim($row['1gb_price_250']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 500 and 1gb
            if ($row['1gb_price_500'] && isset($quantity_name_array['500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['500+'][0]['id'],
                    'price' => $row['1gb_price_500'] != "CALL" ? trim($row['1gb_price_500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 1000 and 1gb
            if ($row['1gb_price_1000'] && isset($quantity_name_array['1000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['1000+'][0]['id'],
                    'price' => $row['1gb_price_1000'] != "CALL" ? trim($row['1gb_price_1000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 2500 and 1gb
            if ($row['1gb_price_2500'] && isset($quantity_name_array['2500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['2500+'][0]['id'],
                    'price' => $row['1gb_price_2500'] != "CALL" ? trim($row['1gb_price_2500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 5000 and 1gb
            if ($row['1gb_price_5000'] && isset($quantity_name_array['5000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['5000+'][0]['id'],
                    'price' => $row['1gb_price_5000'] != "CALL" ? trim($row['1gb_price_5000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 10000 and 1gb
            if ($row['1gb_price_10000'] && isset($quantity_name_array['10000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['10000+'][0]['id'],
                    'price' => $row['1gb_price_10000'] != "CALL" ? trim($row['1gb_price_10000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 50000 and 1gb
            if ($row['1gb_price_50000'] && isset($quantity_name_array['50000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50000+'][0]['id'],
                    'price' => $row['1gb_price_50000'] != "CALL" ? trim($row['1gb_price_50000']) : 0
                ]);
            }
        }

        if (isset($two_gb)) {
            // Creating USB Product Purchase Prices For Quantity 50 and 2gb
            if ($row['2gb_price_50'] && isset($quantity_name_array['50+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50+'][0]['id'],
                    'price' => $row['2gb_price_50'] != "CALL" ? trim($row['2gb_price_50']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 100 and 2gb
            if ($row['2gb_price_100'] && isset($quantity_name_array['100+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['100+'][0]['id'],
                    'price' => $row['2gb_price_100'] != "CALL" ? trim($row['2gb_price_100']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 250 and 2gb
            if ($row['2gb_price_250'] && isset($quantity_name_array['250+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['250+'][0]['id'],
                    'price' => $row['2gb_price_250'] != "CALL" ? trim($row['2gb_price_250']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 500 and 2gb
            if ($row['2gb_price_500'] && isset($quantity_name_array['500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['500+'][0]['id'],
                    'price' => $row['2gb_price_500'] != "CALL" ? trim($row['2gb_price_500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 1000 and 2gb
            if ($row['2gb_price_1000'] && isset($quantity_name_array['1000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['1000+'][0]['id'],
                    'price' => $row['2gb_price_1000'] != "CALL" ? trim($row['2gb_price_1000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 2500 and 2gb
            if ($row['2gb_price_2500'] && isset($quantity_name_array['2500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['2500+'][0]['id'],
                    'price' => $row['2gb_price_2500'] != "CALL" ? trim($row['2gb_price_2500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 5000 and 2gb
            if ($row['2gb_price_5000'] && isset($quantity_name_array['5000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['5000+'][0]['id'],
                    'price' => $row['2gb_price_5000'] != "CALL" ? trim($row['2gb_price_5000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 10000 and 2gb
            if ($row['2gb_price_10000'] && isset($quantity_name_array['10000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['10000+'][0]['id'],
                    'price' => $row['2gb_price_10000'] != "CALL" ? trim($row['2gb_price_10000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 50000 and 2gb
            if ($row['2gb_price_50000'] && isset($quantity_name_array['50000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50000+'][0]['id'],
                    'price' => $row['2gb_price_50000'] != "CALL" ? trim($row['2gb_price_50000']) : 0
                ]);
            }
        }

        if (isset($four_gb)) {
            // Creating USB Product Purchase Prices For Quantity 50 and 4gb
            if ($row['4gb_price_50'] && isset($quantity_name_array['50+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50+'][0]['id'],
                    'price' => $row['4gb_price_50'] != "CALL" ? trim($row['4gb_price_50']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 100 and 4gb
            if ($row['4gb_price_100'] && isset($quantity_name_array['100+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['100+'][0]['id'],
                    'price' => $row['4gb_price_100'] != "CALL" ? trim($row['4gb_price_100']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 250 and 4gb
            if ($row['4gb_price_250'] && isset($quantity_name_array['250+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['250+'][0]['id'],
                    'price' => $row['4gb_price_250'] != "CALL" ? trim($row['4gb_price_250']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 500 and 4gb
            if ($row['4gb_price_500'] && isset($quantity_name_array['500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['500+'][0]['id'],
                    'price' => $row['4gb_price_500'] != "CALL" ? trim($row['4gb_price_500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 1000 and 4gb
            if ($row['4gb_price_1000'] && isset($quantity_name_array['1000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['1000+'][0]['id'],
                    'price' => $row['4gb_price_1000'] != "CALL" ? trim($row['4gb_price_1000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 2500 and 4gb
            if ($row['4gb_price_2500'] && isset($quantity_name_array['2500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['2500+'][0]['id'],
                    'price' => $row['4gb_price_2500'] != "CALL" ? trim($row['4gb_price_2500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 5000 and 4gb
            if ($row['4gb_price_5000'] && isset($quantity_name_array['5000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['5000+'][0]['id'],
                    'price' => $row['4gb_price_5000'] != "CALL" ? trim($row['4gb_price_5000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 10000 and 4gb
            if ($row['4gb_price_10000'] && isset($quantity_name_array['10000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['10000+'][0]['id'],
                    'price' => $row['4gb_price_10000'] != "CALL" ? trim($row['4gb_price_10000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 50000 and 4gb
            if ($row['4gb_price_50000'] && isset($quantity_name_array['50000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50000+'][0]['id'],
                    'price' => $row['4gb_price_50000'] != "CALL" ? trim($row['4gb_price_50000']) : 0
                ]);
            }
        }

        if (isset($eight_gb)) {
            // Creating USB Product Purchase Prices For Quantity 50 and 8gb
            if ($row['8gb_price_50'] && isset($quantity_name_array['50+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50+'][0]['id'],
                    'price' => $row['8gb_price_50'] != "CALL" ? trim($row['8gb_price_50']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 100 and 8gb
            if ($row['8gb_price_100'] && isset($quantity_name_array['100+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['100+'][0]['id'],
                    'price' => $row['8gb_price_100'] != "CALL" ? trim($row['8gb_price_100']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 250 and 8gb
            if ($row['8gb_price_250'] && isset($quantity_name_array['250+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['250+'][0]['id'],
                    'price' => $row['8gb_price_250'] != "CALL" ? trim($row['8gb_price_250']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 500 and 8gb
            if ($row['8gb_price_500'] && isset($quantity_name_array['500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['500+'][0]['id'],
                    'price' => $row['8gb_price_500'] != "CALL" ? trim($row['8gb_price_500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 1000 and 8gb
            if ($row['8gb_price_1000'] && isset($quantity_name_array['1000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['1000+'][0]['id'],
                    'price' => $row['8gb_price_1000'] != "CALL" ? trim($row['8gb_price_1000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 2500 and 8gb
            if ($row['8gb_price_2500'] && isset($quantity_name_array['2500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['2500+'][0]['id'],
                    'price' => $row['8gb_price_2500'] != "CALL" ? trim($row['8gb_price_2500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 5000 and 8gb
            if ($row['8gb_price_5000'] && isset($quantity_name_array['5000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['5000+'][0]['id'],
                    'price' => $row['8gb_price_5000'] != "CALL" ? trim($row['8gb_price_5000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 10000 and 8gb
            if ($row['8gb_price_10000'] && isset($quantity_name_array['10000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['10000+'][0]['id'],
                    'price' => $row['8gb_price_10000'] != "CALL" ? trim($row['8gb_price_10000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 50000 and 8gb
            if ($row['8gb_price_50000'] && isset($quantity_name_array['50000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50000+'][0]['id'],
                    'price' => $row['8gb_price_50000'] != "CALL" ? trim($row['8gb_price_50000']) : 0
                ]);
            }
        }

        if (isset($sixteen_gb)) {
            // Creating USB Product Purchase Prices For Quantity 50 and 16gb
            if ($row['16gb_price_50'] && isset($quantity_name_array['50+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixteen_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50+'][0]['id'],
                    'price' => $row['16gb_price_50'] != "CALL" ? trim($row['16gb_price_50']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 100 and 16gb
            if ($row['16gb_price_100'] && isset($quantity_name_array['100+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixteen_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['100+'][0]['id'],
                    'price' => $row['16gb_price_100'] != "CALL" ? trim($row['16gb_price_100']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 250 and 16gb
            if ($row['16gb_price_250'] && isset($quantity_name_array['250+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixteen_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['250+'][0]['id'],
                    'price' => $row['16gb_price_250'] != "CALL" ? trim($row['16gb_price_250']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 500 and 16gb
            if ($row['16gb_price_500'] && isset($quantity_name_array['500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixteen_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['500+'][0]['id'],
                    'price' => $row['16gb_price_500'] != "CALL" ? trim($row['16gb_price_500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 1000 and 16gb
            if ($row['16gb_price_1000'] && isset($quantity_name_array['1000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixteen_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['1000+'][0]['id'],
                    'price' => $row['16gb_price_1000'] != "CALL" ? trim($row['16gb_price_1000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 2500 and 16gb
            if ($row['16gb_price_2500'] && isset($quantity_name_array['2500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixteen_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['2500+'][0]['id'],
                    'price' => $row['16gb_price_2500'] != "CALL" ? trim($row['16gb_price_2500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 5000 and 16gb
            if ($row['16gb_price_5000'] && isset($quantity_name_array['5000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixteen_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['5000+'][0]['id'],
                    'price' => $row['16gb_price_5000'] != "CALL" ? trim($row['16gb_price_5000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 10000 and 16gb
            if ($row['16gb_price_10000'] && isset($quantity_name_array['10000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixteen_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['10000+'][0]['id'],
                    'price' => $row['16gb_price_10000'] != "CALL" ? trim($row['16gb_price_10000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 50000 and 16gb
            if ($row['16gb_price_50000'] && isset($quantity_name_array['50000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixteen_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50000+'][0]['id'],
                    'price' => $row['16gb_price_50000'] != "CALL" ? trim($row['16gb_price_50000']) : 0
                ]);
            }
        }

        if (isset($thirty_two_gb)) {
            // Creating USB Product Purchase Prices For Quantity 50 and 32gb
            if ($row['32gb_price_50'] && isset($quantity_name_array['50+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $thirty_two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50+'][0]['id'],
                    'price' => $row['32gb_price_50'] != "CALL" ? trim($row['32gb_price_50']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 100 and 32gb
            if ($row['32gb_price_100'] && isset($quantity_name_array['100+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $thirty_two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['100+'][0]['id'],
                    'price' => $row['32gb_price_100'] != "CALL" ? trim($row['32gb_price_100']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 250 and 32gb
            if ($row['32gb_price_250'] && isset($quantity_name_array['250+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $thirty_two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['250+'][0]['id'],
                    'price' => $row['32gb_price_250'] != "CALL" ? trim($row['32gb_price_250']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 500 and 32gb
            if ($row['32gb_price_500'] && isset($quantity_name_array['500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $thirty_two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['500+'][0]['id'],
                    'price' => $row['32gb_price_500'] != "CALL" ? trim($row['32gb_price_500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 1000 and 32gb
            if ($row['32gb_price_1000'] && isset($quantity_name_array['1000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $thirty_two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['1000+'][0]['id'],
                    'price' => $row['32gb_price_1000'] != "CALL" ? trim($row['32gb_price_1000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 2500 and 32gb
            if ($row['32gb_price_2500'] && isset($quantity_name_array['2500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $thirty_two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['2500+'][0]['id'],
                    'price' => $row['32gb_price_2500'] != "CALL" ? trim($row['32gb_price_2500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 5000 and 32gb
            if ($row['32gb_price_5000'] && isset($quantity_name_array['5000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $thirty_two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['5000+'][0]['id'],
                    'price' => $row['32gb_price_5000'] != "CALL" ? trim($row['32gb_price_5000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 10000 and 32gb
            if ($row['32gb_price_10000'] && isset($quantity_name_array['10000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $thirty_two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['10000+'][0]['id'],
                    'price' => $row['32gb_price_10000'] != "CALL" ? trim($row['32gb_price_10000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 50000 and 32gb
            if ($row['32gb_price_50000'] && isset($quantity_name_array['50000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $thirty_two_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50000+'][0]['id'],
                    'price' => $row['32gb_price_50000'] != "CALL" ? trim($row['32gb_price_50000']) : 0
                ]);
            }
        }

        if (isset($sixty_four_gb)) {
            // Creating USB Product Purchase Prices For Quantity 50 and 64gb
            if ($row['64gb_price_50'] && isset($quantity_name_array['50+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixty_four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50+'][0]['id'],
                    'price' => $row['64gb_price_50'] != "CALL" ? trim($row['64gb_price_50']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 100 and 64gb
            if ($row['64gb_price_100'] && isset($quantity_name_array['100+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixty_four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['100+'][0]['id'],
                    'price' => $row['64gb_price_100'] != "CALL" ? trim($row['64gb_price_100']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 250 and 64gb
            if ($row['64gb_price_250'] && isset($quantity_name_array['250+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixty_four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['250+'][0]['id'],
                    'price' => $row['64gb_price_250'] != "CALL" ? trim($row['64gb_price_250']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 500 and 64gb
            if ($row['64gb_price_500'] && isset($quantity_name_array['500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixty_four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['500+'][0]['id'],
                    'price' => $row['64gb_price_500'] != "CALL" ? trim($row['64gb_price_500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 1000 and 64gb
            if ($row['64gb_price_1000'] && isset($quantity_name_array['1000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixty_four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['1000+'][0]['id'],
                    'price' => $row['64gb_price_1000'] != "CALL" ? trim($row['64gb_price_1000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 2500 and 64gb
            if ($row['64gb_price_2500'] && isset($quantity_name_array['2500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixty_four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['2500+'][0]['id'],
                    'price' => $row['64gb_price_2500'] != "CALL" ? trim($row['64gb_price_2500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 5000 and 64gb
            if ($row['64gb_price_5000'] && isset($quantity_name_array['5000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixty_four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['5000+'][0]['id'],
                    'price' => $row['64gb_price_5000'] != "CALL" ? trim($row['64gb_price_5000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 10000 and 64gb
            if ($row['64gb_price_10000'] && isset($quantity_name_array['10000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixty_four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['10000+'][0]['id'],
                    'price' => $row['64gb_price_10000'] != "CALL" ? trim($row['64gb_price_10000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 50000 and 64gb
            if ($row['64gb_price_50000'] && isset($quantity_name_array['50000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $sixty_four_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50000+'][0]['id'],
                    'price' => $row['64gb_price_50000'] != "CALL" ? trim($row['64gb_price_50000']) : 0
                ]);
            }
        }

        if (isset($one_twenty_eight_gb)) {
            // Creating USB Product Purchase Prices For Quantity 50 and 128gb
            if ($row['128gb_price_50'] && isset($quantity_name_array['50+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_twenty_eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50+'][0]['id'],
                    'price' => $row['128gb_price_50'] != "CALL" ? trim($row['128gb_price_50']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 100 and 128gb
            if ($row['128gb_price_100'] && isset($quantity_name_array['100+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_twenty_eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['100+'][0]['id'],
                    'price' => $row['128gb_price_100'] != "CALL" ? trim($row['128gb_price_100']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 250 and 128gb
            if ($row['128gb_price_250'] && isset($quantity_name_array['250+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_twenty_eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['250+'][0]['id'],
                    'price' => $row['128gb_price_250'] != "CALL" ? trim($row['128gb_price_250']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 500 and 128gb
            if ($row['128gb_price_500'] && isset($quantity_name_array['500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_twenty_eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['500+'][0]['id'],
                    'price' => $row['128gb_price_500'] != "CALL" ? trim($row['128gb_price_500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 1000 and 128gb
            if ($row['128gb_price_1000'] && isset($quantity_name_array['1000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_twenty_eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['1000+'][0]['id'],
                    'price' => $row['128gb_price_1000'] != "CALL" ? trim($row['128gb_price_1000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 2500 and 128gb
            if ($row['128gb_price_2500'] && isset($quantity_name_array['2500+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_twenty_eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['2500+'][0]['id'],
                    'price' => $row['128gb_price_2500'] != "CALL" ? trim($row['128gb_price_2500']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 5000 and 128gb
            if ($row['128gb_price_5000'] && isset($quantity_name_array['5000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_twenty_eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['5000+'][0]['id'],
                    'price' => $row['128gb_price_5000'] != "CALL" ? trim($row['128gb_price_5000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 10000 and 128gb
            if ($row['128gb_price_10000'] && isset($quantity_name_array['10000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_twenty_eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['10000+'][0]['id'],
                    'price' => $row['128gb_price_10000'] != "CALL" ? trim($row['128gb_price_10000']) : 0
                ]);
            }

            // Creating USB Product Purchase Prices For Quantity 50000 and 128gb
            if ($row['128gb_price_50000'] && isset($quantity_name_array['50000+'][0]['id'])) {
                UsbPurchasePrice::create([
                    'usb_type_id' => $one_twenty_eight_gb,
                    'product_id' => $product->id,
                    'quantity_id' => $quantity_name_array['50000+'][0]['id'],
                    'price' => $row['128gb_price_50000'] != "CALL" ? trim($row['128gb_price_50000']) : 0
                ]);
            }
        }
    }

    /**
     * @param array $row
     * @param $product
     */
    public function createProductCategory(array $row, $product): void
    {
        // Removing Categories For Product If Any
        $product->categories()->detach();

        // Getting All The Main Categories
        $this->main_category_array = Category::where('parent_id', null)
            ->get(['name', 'id'])
            ->keyBy('name')
            ->toArray();

        // Getting The Sub Categories Of The Given Main Category
        if (isset($this->main_category_array[$row['main_category']]['id'])) {
            $this->sub_categories_of_main_category_array = Category::where('parent_id', $this->main_category_array[$row['main_category']]['id'])
                ->get(['name', 'id'])
                ->keyBy('name')
                ->toArray();
        }

        // Getting The Sub Sub Categories Of The Given Sub Category
        if (isset($this->sub_categories_of_main_category_array[$row['sub_category']]['id'])) {
            $this->sub_sub_categories_of_sub_category_array = Category::where('parent_id', $this->sub_categories_of_main_category_array[$row['sub_category']]['id'])
                ->get(['name', 'id', 'parent_id'])
                ->keyBy('name')
                ->toArray();
        }

        // Storing Main Category For the Product
        if (isset($this->main_category_array[$row['main_category']]['id'])) {
            $product->categories()
                ->attach($this->main_category_array[$row['main_category']]['id'], ['level' => 1]);
        }

        // Storing Sub Category For the Product
        if (isset($this->sub_categories_of_main_category_array[$row['sub_category']]['id'])) {
            $product->categories()
                ->attach($this->sub_categories_of_main_category_array[$row['sub_category']]['id'], ['level' => 2]);
        }

        // Storing Sub Sub Category For the Product
        if (isset($this->sub_sub_categories_of_sub_category_array[$row['sub_sub_category']]['id'])) {
            $product->categories()
                ->attach($this->sub_sub_categories_of_sub_category_array[$row['sub_sub_category']]['id'], ['level' => 3]);
        }
    }


    /**
     * @param array $row
     * @param $product
     * @param $personalisation_type_name_array
     */
    public function createProductPersonalisationType(array $row, $product, $personalisation_type_name_array): void
    {
        // Creating Product Personalisation Type 1
        if ($row['personalisation_type_1'] && isset($personalisation_type_name_array[$row['personalisation_type_1']][0]['id'])) {
            ProductPersonalisationtype::create([
                'product_id' => $product->id,
                'personalisationtype_id' => $personalisation_type_name_array[$row['personalisation_type_1']][0]['id']
            ]);
        }

        // Creating Product Personalisation Type 2
        if ($row['personalisation_type_2'] && isset($personalisation_type_name_array[$row['personalisation_type_2']][0]['id'])) {
            ProductPersonalisationtype::create([
                'product_id' => $product->id,
                'personalisationtype_id' => $personalisation_type_name_array[$row['personalisation_type_2']][0]['id']
            ]);
        }

        // Creating Product Personalisation Type 3
        if ($row['personalisation_type_3'] && isset($personalisation_type_name_array[$row['personalisation_type_3']][0]['id'])) {
            ProductPersonalisationtype::create([
                'product_id' => $product->id,
                'personalisationtype_id' => $personalisation_type_name_array[$row['personalisation_type_3']][0]['id']
            ]);
        }
    }

    /**
     * @param array $row
     * @param $product
     */
    public function createProductAttribute(array $row, $product): void
    {
        // Checking If Attribute Colors Exists
        if ($row['attribute_colors']) {

            // Exploding '|' Separated Attribute Color
            $attribute_colors = explode("|", $row['attribute_colors']);

            // Exploding '|' Separated Attribute Image
            $attribute_color_images = explode("|", $row['attribute_color_images']);

            // Empty Index for Loop Count
            $index = 0;

            // Looping through the Attribute Colors
            foreach ($attribute_colors as $attribute_color) {

                // Exploding ',' Separated Color and Primary Color
                $color_and_primary_color = explode(",", $attribute_color);

                // Getting Primary Color Id
                $primary_color_id = get_primary_color_id_by_name(trim($attribute_color));
                if (count($color_and_primary_color) == 2 && isset($color_and_primary_color[1]))
                    $primary_color_id = get_primary_color_id_by_name(trim($color_and_primary_color[1]));

                // Formatting Attribute Color Name
                $color = str_replace('/', ' / ', str_replace('+', ' and ', $color_and_primary_color[0]));

                // Checking For The Image Of The Attribute
                $attribute_color_image = isset($attribute_color_images[$index]) ? trim($attribute_color_images[$index]) : "no_image.png";

                // Setting The image
                $image = "no_image.png";
                if (file_exists(public_path('files/23/Photos/Products/') . '/' . seoUrl($row['manufacturer_name']) . '/' . $attribute_color_image))
                    $image = $attribute_color_image;

                // Creating Attribute
                Attribute::create([
                    'name' => trim(str_replace('  /  ', ' / ', str_replace('  +  ', ' and ', $color))),
                    'image' => $image,
                    'product_id' => $product->id,
                    'primarycolor_id' => $primary_color_id
                ]);

                // Attaching Primary Colors To Product
                $product->primary_colors()->attach($primary_color_id);

                // Incrementing Loop Count
                $index++;
            }
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        // Validation
        return [
            'name' => 'required|max:255',
            'product_code' => 'required|max:255|unique:products,product_code',
            'main_image' => 'required|max:255',
            'main_category' => 'required|max:255',
            'sub_category' => 'required|max:255',
            'sub_sub_category' => 'required|max:255',
            'manufacturer_name' => 'required|max:255',
            'product_type' => 'required|max:255',
        ];
    }

    /**
     * Counts Uploaded Rows
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->total_rows;
    }
}
