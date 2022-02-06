<?php

namespace App\Http\Controllers;

use App\Product;
 use App\Category;
 use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) //この変数に$category->idの値が入る。なせ？？
    {
        $sort_query = [];
        //$sort_queyは空の配列
        $sorted = "";
        //$sortedも空の変数
         
         if($request->direction !== null) {
             $sort_query = $request->direction;
             $sorted = $request->sort;
             //$request（受け取る値？）でdirectionが何か不明・・？でnullじゃない時
             //空の配列$sort_queryに、$requestを入れる
             //空の変数$sortedに、sort（$request）を代入
         } else if ($request->sort !== null){
             $slices = explode('', $request->sort);
             $sort_query[$slices[0]] = $slices[1];
             $sorted =$request->sort;
             //$request（受け取ったデータ）がsortで、空じゃ無い時は、
             //$slicesは、explode関数で、''を区切り値として、文字列を、配列のデータとして並べて
             //$sort_queryは配列データの0番目を、1番目として処理？
             //$sortedは$requestできたデータをsortする
         }

        if ($request->category !== null){
            //ユーザーからリクエストがきて、
            $products = Product::where('category_id', $request->category)->sortable($sort_query)->paginate(15);
            // $productsは、DBのProductのカテゴリーIDカラムの、リクエストでもらったカテゴリーと一致している情報をとってくる、かつソートとして出力する&ページネイション
            $total_count = Product::where('category_id', $request->category)->count();
            // $total_countは、DBのProductのカテゴリーID絡むの、リクエストでもらったカテゴリーの総数を出力する
            $category = Category::find($request->category);
            //$categoryはDBのCategoryから、$requestで取得したカテゴリーIDを、CategoryのDBから返してくる
        } else {
            $products = Product::sortable($sort_query)->paginate(15);
            //$productsは、DBのProductの
            $total_count= "";
             $category = null;
        }

        $sort = [
            '並び替え' => '',
            '価格の安い順' => 'price asc',
            '価格の高い順' => 'price desc',
            '出品の古い順' => 'updated_at asc',
            '出品の新しい順' => 'updated_at desc'
        ];

        $categories = Category::all();
        $major_category_names = Category::pluck('major_category_name')->unique();
        //大項目のカテゴリーだけ、連想配列から、取ってきて＝$majpr_category_names
        //それを、重複した値を取り除いて出力して　unique関数

        return view('products.index', compact('products','category','categories','major_category_names', 'total_count', 'sort', 'sorted'));
    }
    //変数$productは、Productのデータベースにある情報を15分割で表示
    //products.indexディレクトリに、compact関数を使って、変数productsを渡す
    //だから、indexページに、ページネイションが実装される
    //compact関数で、変数categoriesも表示

    public function favorite(Product $product)
    {
        $user = Auth::user();

        if($user->hasFavorited($product)){
            $user->unfavorite($product);
        } else {
            $user->favorite($product);
        }

        return redirect()->route('products.show', $product);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product();
        $product -> name = $request->input('name');
        $product ->description = $request->input('description');
        $product ->price = $request->input('price');
         $product->category_id = $request->input('category_id');
        $product ->save();

        return redirect()->route('products.show',['id' => $product->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $reviews = $product->reviews()->get();

        return view('products.show',compact('product','reviews'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('products.edit', compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
         $product->category_id = $request ->input('category_id');
        $product->update();

        return redirect()->route('products.show',['id' => $product->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index');
    }
}
