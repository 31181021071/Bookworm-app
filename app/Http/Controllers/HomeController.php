<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use App\Models\Discount;
use App\Models\User;


class HomeController extends Controller
{
    public function index(){
        return $this->getTop10Discount();
    }

    public function getTop10Discount(){
        $selectedTop10Discount = Book::leftJoin('discount','discount.book_id','book.id')
            ->select('book.id','book_title','book_price',"discount_price",DB::raw('book_price-discount_price as sub_price'))
            ->whereNotNull('discount_price')
            ->orderby('sub_price','desc')
            ->limit(10)
            ->get();
        return [
            'books'=>$selectedTop10Discount
        ];
    }


}
