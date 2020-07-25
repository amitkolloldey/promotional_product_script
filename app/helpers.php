<?php

use App\Product;
use App\Category;
use App\Quantity;
use App\Attribute;
use App\Manufacturer;
use App\PrimaryColor;
use App\Purchaseprice;
use App\Categorymarkup;
use App\PrintingAgency;
use App\UsbPurchasePrice;
use App\Personalisationtype;
use App\PersonalisationPrice;
use App\Personalisationtypemarkup;
use Illuminate\Support\Facades\DB;
use App\Personalisationoptionvalue;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

// Custom Pagination For Array
if (!function_exists('customPaginate')) {
    function customPaginate($items, $pagination_route, $perPage = 20, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path' => $pagination_route]);
    }
}

// Defining Redirect To URL
if (!function_exists('customRedirectTo')) {
    function customRedirectTo($redirectTo)
    {
        if (isset(session()->get('url')['intended'])) {
            return $redirectTo = session()->get('url')['intended'];
        } elseif (count(\Cart::getContent())) {
            return $redirectTo = "order/checkout";
        }

        return $redirectTo = "/userprofile";
    }
}

// Converting Name to Seo Url
if (!function_exists('seoUrl')) {
    function seoUrl($string)
    {
        // Lower case everything
        $string = strtolower($string);
        // Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        // Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        // Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }
}

// Short Long Description
if (!function_exists('shortenDescription')) {
    function shortenDescription($text, $limit)
    {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
    }
}

// Get Min Purchase Quantity
if (!function_exists('get_min_purchase_quantity')) {
    function get_min_purchase_quantity($id)
    {
        $purchase_prices = Purchaseprice::where('product_id', $id)->where('price', '!=', 0)->get('qty_id');
        $quantity_amount = [];
        foreach ($purchase_prices as $purchase_price) {
            $values = Quantity::where('id', $purchase_price->qty_id)->get('min_qty');
            foreach ($values as $value) {
                $quantity_amount[] = $value->min_qty;
            }
        }
        return min($quantity_amount);
    }
}

// Get Max Purchase Quantity
if (!function_exists('get_max_purchase_quantity')) {
    function get_max_purchase_quantity($id)
    {
        $purchase_prices = Purchaseprice::where('product_id', $id)->where('price', '!=', 0)->get('qty_id');
        $quantity_amount = [];
        foreach ($purchase_prices as $purchase_price) {
            $values = Quantity::where('id', $purchase_price->qty_id)->get('min_qty');
            foreach ($values as $value) {
                $quantity_amount[] = $value->min_qty;
            }
        }
        return max($quantity_amount);
    }
}


// Get Min Quantity
if (!function_exists('get_min_quantity')) {
    function get_min_quantity($id)
    {
        $quantities = Quantity::where('id', $id)->get('min_qty');
        foreach ($quantities as $quantity) {
            return $quantity->min_qty;
        }
        return Null;
    }
}

// Get Max Quantity
if (!function_exists('get_max_quantity')) {
    function get_max_quantity($id)
    {
        $quantities = Quantity::where('id', $id)->get('max_qty');
        foreach ($quantities as $quantity) {
            return $quantity->max_qty;
        }
        return Null;
    }
}




// Get Min/Max Purchase Price
if (!function_exists('get_min_max_purchase_price')) {
    function get_min_max_purchase_price($id)
    {
        $purchase_price = Purchaseprice::where('product_id', $id)->where('price', '!=', 0)->get();
        return $purchase_price;
    }
}



// Get Min/Max usb Purchase Price
if (!function_exists('get_min_max_usb_purchase_price')) {
    function get_min_max_usb_purchase_price($id)
    {
        $usb_purchase_price = UsbPurchasePrice::where('product_id', $id)->where('price', '!=', 0)->get();
        return $usb_purchase_price;
    }
}

// Returns Product Code By Id
if (!function_exists('get_product_code_by_id')) {
    function get_product_code_by_id($id)
    {
        $products = Product::where('id', $id)->get('product_code');
        foreach ($products as $product) {
            return $product->product_code;
        }
        return Null;
    }
}

// Returns Product Is new or Not (Yes / No)
if (!function_exists('is_new_product')) {
    function is_new_product($id)
    {
        $products = DB::table('category_product')->where('product_id', $id)->get('is_new');

        foreach ($products as $product) {
            return $product->is_new == 1 ? "<i class='fa fa-check'></i>" : "NO";
        }
        return Null;
    }
}

// Returns Product Is popular or Not (Yes / No)
if (!function_exists('is_popular_product')) {
    function is_popular_product($id)
    {
        $products = DB::table('category_product')->where('product_id', $id)->get('is_popular');
        foreach ($products as $product) {
            return $product->is_popular == "1" ? "YES" : "NO";
        }
        return Null;
    }
}


// Returns Product Is Discontinued Stock or Not (Yes / No)
if (!function_exists('is_discontinued_stock')) {
    function is_discontinued_stock($id)
    {
        $products = Product::where('id', $id)->get('discontinued_stock');
        foreach ($products as $product) {
            return $product->discontinued_stock == 1 ? "YES" : "NO";
        }
        return Null;
    }
}

// Returns Category Markup Price
if (!function_exists('get_category_markup_by_category_id_and_quantity_id()')) {
    function get_category_markup_by_category_id_and_quantity_id($category_id, $qty_id)
    {

        $category_markups = Categorymarkup::where('category_id', $category_id)
            ->where('qty_id', $qty_id)
            ->get('lc_price');
        foreach ($category_markups as $category_markup) {
            return $category_markup->lc_price;
        }
        return Null;
    }
}

// Returns Product Purchase Price
if (!function_exists('get_product_purchase_price_by_product_id_and_quantity_id()')) {
    function get_product_purchase_price_by_product_id_and_quantity_id($product_id, $quantity_id)
    {
        $purchase_prices = Purchaseprice::where('product_id', $product_id)
            ->where('qty_id', $quantity_id)
            ->get('price');
        foreach ($purchase_prices as $purchase_price) {
            return $purchase_price->price;
        }
        return Null;
    }
}

// Returns Personalisation Type Markup Price
if (!function_exists('get_personalisationtype_markup_by_personalisationtype_id_and_quantity_id()')) {
    function get_personalisationtype_markup_by_personalisationtype_id_and_quantity_id($personalisationtype_id, $quantity_id)
    {
        $personalisationtype_markups = Personalisationtypemarkup::where('personalisationtype_id', $personalisationtype_id)
            ->where('qty_id', $quantity_id)
            ->get('lc_price');
        foreach ($personalisationtype_markups as $personalisationtype_markup) {
            return $personalisationtype_markup->lc_price;
        }
        return Null;
    }
}


// Returns Product Id By The Slug
if (!function_exists('get_product_id_by_slug()')) {
    function get_product_id_by_slug($slug)
    {
        $product_ids = Product::where('slug', $slug)->get('id');
        if ($product_ids) {
            foreach ($product_ids as $product) {
                return $product->id;
            }
            return null;
        }
        return null;
    }
}

// Returns Product Slug By The Id
if (!function_exists('get_product_slug_by_id()')) {
    function get_product_slug_by_id($id)
    {
        $product_slugs = Product::where('id', $id)->get('slug');
        if ($product_slugs) {
            foreach ($product_slugs as $product) {
                return $product->slug;
            }
            return null;
        }
        return null;
    }
}


// Returns Product Slug By The Id
if (!function_exists('get_attribute_name_by_id()')) {
    function get_attribute_name_by_id($id)
    {
        $attributes = Attribute::where('id', $id)->get('name');
        if ($attributes) {
            foreach ($attributes as $attribute) {
                return $attribute->name;
            }
            return "None";
        }
        return "None";
    }
}


// Returns Personalisation Color/Size/Position By The Ids
if (!function_exists('get_personalisation_color_by_ids()')) {
    function get_personalisation_color_by_ids($id)
    {
        $personalisation_agency_size_color = explode('_', $id);
        if (sizeof($personalisation_agency_size_color) != 4) {
            return "None";
        }
        $personalisation_color_name = get_personalisationoptionvalue_by_id($personalisation_agency_size_color[2]) . ' & ' . get_personalisationoptionvalue_by_id($personalisation_agency_size_color[3]);

        return $personalisation_color_name;
    }
}


// Returns Parent Category Name By Parent Id
if (!function_exists('get_parent_category_name_by_parent_id')) {
    function get_parent_category_name_by_parent_id($id)
    {
        $categories = Category::where('id', $id)->get('name');
        foreach ($categories as $category) {
            return $category->name;
        }
        return "None";
    }
}

// Returns Parent Category Name By Sub Category Id
if (!function_exists('get_parent_category_name_by_subcategory_id')) {
    function get_parent_category_name_by_subcategory_id($id)
    {
        $categories = Category::where('id', $id)->get('parent_id');
        foreach ($categories as $category) {
            $parents = Category::where('id', $category->parent_id)->get(['name', 'slug']);
            foreach ($parents as $parent) {
                return $parent;
            }
            return null;
        }
        return null;
    }
}

// Returns Primary Color Name By Id
if (!function_exists('get_primary_color_name_by_id')) {
    function get_primary_color_name_by_id($id)
    {
        $primary_color = PrimaryColor::where('id', $id)->get('name');
        if ($primary_color) {
            foreach ($primary_color as $color) {
                return $color->name;
            }
            return "None";
        }
        return "None";
    }
}


// Returns Primary Color Id By Name
if (!function_exists('get_primary_color_id_by_name')) {
    function get_primary_color_id_by_name($name)
    {
        $primary_color = PrimaryColor::where('name', $name)->get('id');
        if ($primary_color) {
            foreach ($primary_color as $color) {
                return $color->id;
            }
            return null;
        }
        return null;
    }
}

// Returns Printing Agency Name By Id
if (!function_exists('get_printing_agency_name_by_id()')) {
    function get_printing_agency_name_by_id($id)
    {
        $printing_agencies = PrintingAgency::where('id', $id)->get('name');
        if ($printing_agencies) {
            foreach ($printing_agencies as $printing_agency) {
                return $printing_agency->name;
            }
            return "None";
        }
        return "None";
    }
}

// Returns Quantity Title By ID
if (!function_exists('get_quantity_title_by_id()')) {
    function get_quantity_title_by_id($id)
    {
        $quantity_title = Quantity::where('id', $id)->get('title');

        if ($quantity_title) {
            foreach ($quantity_title as $title) {
                return $title->title;
            }
            return "None";
        }
        return "None";
    }
}

// Returns USB Quantity Title By ID
if (!function_exists('get_usb_quantity_title_by_id()')) {
    function get_usb_quantity_title_by_id($id)
    {
        $quantity_title = Quantity::where('id', $id)->get('title');

        return $quantity_title;
    }
}

// Returns Quantity Id By The Name
if (!function_exists('get_quantity_id_by_name()')) {
    function get_quantity_id_by_name($title)
    {
        $quantity = Quantity::where('title', $title)
            ->get('id');
        if (count($quantity)) {
            foreach ($quantity as $quantity_id) {
                if ($quantity_id->id) {
                    return $quantity_id->id;
                }
                return null;
            }
        }
        return null;
    }
}

// Returns Personalisation Type Id By The Name
if (!function_exists('get_personalisation_type_by_name()')) {
    function get_personalisation_type_by_name($name)
    {
        $personalisation_type = Personalisationtype::where('name', $name)
            ->get('id');
        if (count($personalisation_type)) {
            foreach ($personalisation_type as $personalisation_type_id) {
                if ($personalisation_type_id->id) {
                    return $personalisation_type_id->id;
                }
                return null;
            }
        }
        return null;
    }
}

// Returns Personalisation Option Value By The ID
if (!function_exists('get_personalisationoptionvalue_by_id()')) {
    function get_personalisationoptionvalue_by_id($id)
    {
        $personalisationoptionvalues = Personalisationoptionvalue::where('id', $id)->get('value');

        if ($personalisationoptionvalues) {
            foreach ($personalisationoptionvalues as $personalisationoptionvalue) {
                return $personalisationoptionvalue->value;
            }
            return "None";
        }
        return "None";
    }
}


// Returns Personalisation Price by Attribute IDs
if (!function_exists('get_personalisation_price_by_attribute_ids()')) {
    function get_personalisation_price_by_attribute_ids($personalisationtype_id, $printing_agency_id, $size_id, $quantity_id, $color_position_id)
    {

        $personalisation_prices = PersonalisationPrice::where('personalisationtype_id', $personalisationtype_id)
            ->where('printingagency_id', $printing_agency_id)
            ->where('size_id', $size_id)
            ->where('quantity_id', $quantity_id)
            ->where('color_position_id', $color_position_id)
            ->get('price');

        if ($personalisation_prices) {
            foreach ($personalisation_prices as $personalisation_price) {
                if ($personalisation_price->price) {
                    return $personalisation_price->price;
                }
                return "0";
            }
            return "0";
        }
        return "0";
    }
}


// Returns Category La Markup Price For A Quantity
if (!function_exists('get_category_la_markup_price()')) {
    function get_category_la_markup_price($category_id, $qty_id)
    {
        $la_markup_prices = Categorymarkup::where('category_id', $category_id)
            ->where('qty_id', $qty_id)
            ->get('la_price');
        if ($la_markup_prices) {
            foreach ($la_markup_prices as $la_markup_price) {
                if ($la_markup_price->la_price) {
                    return $la_markup_price->la_price;
                }
                return "0";
            }
            return "0";
        }
        return "0";
    }
}


// Returns Category LB Markup Price For A Quantity
if (!function_exists('get_category_lb_markup_price()')) {
    function get_category_lb_markup_price($category_id, $qty_id)
    {
        $lb_markup_prices = Categorymarkup::where('category_id', $category_id)
            ->where('qty_id', $qty_id)
            ->get('lb_price');
        if ($lb_markup_prices) {
            foreach ($lb_markup_prices as $lb_markup_price) {
                if ($lb_markup_price->lb_price) {
                    return $lb_markup_price->lb_price;
                }
                return "0";
            }
            return "0";
        }
        return "0";
    }
}


// Returns Category LC Markup Price For A Quantity
if (!function_exists('get_category_lc_markup_price()')) {
    function get_category_lc_markup_price($category_id, $qty_id)
    {
        $lc_markup_prices = Categorymarkup::where('category_id', $category_id)
            ->where('qty_id', $qty_id)
            ->get('lc_price');
        if ($lc_markup_prices) {
            foreach ($lc_markup_prices as $lc_markup_price) {
                if ($lc_markup_price->lc_price) {
                    return $lc_markup_price->lc_price;
                }
                return "0";
            }
            return "0";
        }
        return "0";
    }
}


// Returns Personalisation Type La Markup Price For A Quantity
if (!function_exists('get_personalisation_type_la_markup_price()')) {
    function get_personalisation_type_la_markup_price($personalisation_type_id, $qty_id)
    {
        $la_markup_prices = Personalisationtypemarkup::where('personalisationtype_id', $personalisation_type_id)
            ->where('qty_id', $qty_id)
            ->get('la_price');
        if ($la_markup_prices) {
            foreach ($la_markup_prices as $la_markup_price) {
                if ($la_markup_price->la_price) {
                    return $la_markup_price->la_price;
                }
                return "0";
            }
            return "0";
        }
        return "0";
    }
}


// Returns Personalisation Type Lb Markup Price For A Quantity
if (!function_exists('get_personalisation_type_lb_markup_price()')) {
    function get_personalisation_type_lb_markup_price($personalisation_type_id, $qty_id)
    {
        $lb_markup_prices = Personalisationtypemarkup::where('personalisationtype_id', $personalisation_type_id)
            ->where('qty_id', $qty_id)
            ->get('lb_price');
        if ($lb_markup_prices) {
            foreach ($lb_markup_prices as $lb_markup_price) {
                if ($lb_markup_price->lb_price) {
                    return $lb_markup_price->lb_price;
                }
                return "0";
            }
            return "0";
        }
        return "0";
    }
}


// Returns Personalisation Type Lc Markup Price For A Quantity
if (!function_exists('get_personalisation_type_lc_markup_price()')) {
    function get_personalisation_type_lc_markup_price($personalisation_type_id, $qty_id)
    {
        $lc_markup_prices = Personalisationtypemarkup::where('personalisationtype_id', $personalisation_type_id)
            ->where('qty_id', $qty_id)
            ->get('lc_price');
        if ($lc_markup_prices) {
            foreach ($lc_markup_prices as $lc_markup_price) {
                if ($lc_markup_price->lc_price) {
                    return $lc_markup_price->lc_price;
                }
                return "0";
            }
            return "0";
        }
        return "0";
    }
}


// Returns Promo Product's Purchase Price By Product ID and Quantity ID
if (!function_exists('get_promo_price()')) {
    function get_promo_price($product_id, $qty_id)
    {
        $purchase_prices = Purchaseprice::where('product_id', $product_id)
            ->where('qty_id', $qty_id)
            ->get();
        if ($purchase_prices) {
            foreach ($purchase_prices as $purchase_price) {
                return $purchase_price->price;
            }
            return "0";
        }
        return "0";
    }
}



// Returns USB Product's Purchase Price By USB Type ID, Product ID and Quantity ID
if (!function_exists('get_usb_price()')) {
    function get_usb_price($usb_type_id, $product_id, $quantity_id)
    {
        $usb_purchase_prices = UsbPurchasePrice::where('usb_type_id', $usb_type_id)
            ->where('product_id', $product_id)
            ->where('quantity_id', $quantity_id)
            ->get('price');

        if (count($usb_purchase_prices)) {
            foreach ($usb_purchase_prices as $usb_purchase_price) {
                if ($usb_purchase_price->price) {

                    return $usb_purchase_price->price;
                }
                return "0";
            }
        }
        return "0";
    }
}


// Returns Personalisation Type Name By ID
if (!function_exists('get_personalisation_type_name_by_id()')) {
    function get_personalisation_type_name_by_id($id)
    {
        $personalisationtypes = Personalisationtype::where('id', $id)
            ->get('name');

        if (count($personalisationtypes)) {
            foreach ($personalisationtypes as $personalisationtype) {
                if ($personalisationtype->name) {

                    return $personalisationtype->name;
                }
                return "None";
            }
        }
        return "None";
    }
}


// Returns Manufacturer ID From Name
if (!function_exists('get_manufacturer_id_by_name()')) {
    function get_manufacturer_id_by_name($name)
    {
        $manufacturers = Manufacturer::where('name', $name)
            ->get('id');
        if (count($manufacturers)) {
            foreach ($manufacturers as $manufacturer) {
                if ($manufacturer->id) {
                    return $manufacturer->id;
                }
                return Null;
            }
        }
        return Null;
    }
}


// Returns Manufacturer Name By ID
if (!function_exists('get_manufacturer_name_by_id()')) {
    function get_manufacturer_name_by_id($id)
    {
        $manufacturers = Manufacturer::where('id', $id)
            ->get('name');

        if (count($manufacturers)) {
            foreach ($manufacturers as $manufacturer) {
                if ($manufacturer->name) {

                    return $manufacturer->name;
                }
                return "None";
            }
        }
        return "None";
    }
}


// Returns Category ID From Name
if (!function_exists('get_category_id_by_name()')) {
    function get_category_id_by_name($name)
    {
        $categories = Category::where('name', $name)
            ->get('id');

        if (count($categories)) {
            foreach ($categories as $category) {
                if ($category->id) {
                    return $category->id;
                }
                return null;
            }
        }
        return null;
    }
}


// Generation Css Class Name From Personalisation Option
if (!function_exists('generate_class_name()')) {
    function generate_class_name($name)
    {
        $class = strtolower(str_replace(' ', '_', $name));
        if ($class == "color") {
            $class = "color";
        } elseif ($class == "position") {
            $class = "position";
        } else {
            $class = "size";
        }
        return $class;
    }
}
