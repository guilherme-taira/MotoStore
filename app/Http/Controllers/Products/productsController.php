<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class productsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "MotoStore Produtos";
        $viewData['products'] = Products::all();
        return view('admin.products')->with('viewData', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5',
            'price' => 'required|numeric|min:1',
            'image' => 'required|file',
            "stock" => "required|numeric",
            'description' => 'required|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $produto = new Products();
        $produto->name = $request->name;
        $produto->price = $request->price;
        $produto->description = $request->description;
        $produto->stock = $request->stock;
        $produto->image = 'image.png';
        $produto->save();

        if ($request->hasFile('image')) {
            //$imageName = $produto->getId() . "." . $request->file('image')->extension();
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $file->storeAs('produtos/'.$produto->getId(),$filename,'s3');
            $produto->setImage($filename);
            $produto->save();
        }

        return redirect()->route('products.store');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produto = Products::findOrFail($id);

        if ($produto) {
            $viewData = [];
            $viewData['title'] = "MotoStore : " . $produto->name;
            $viewData['subtitle'] = $produto->name;
            $viewData['product'] = $produto;
            $viewData['stock'] = $produto->stock;
            $viewData['image'] = $produto->image;
            return view('store.show')->with('viewData', $viewData);
        }

        return redirect()->route('store.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $produto = Products::where('id', $id)->first();

        $viewData = [];
        $viewData['title'] = "MotoStore" . $produto->getName();
        $viewData['product'] = $produto;
        return view('products.edit')->with('viewData', $viewData);
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
        $request->validate([
            "name" => "required|max:255",
            "description" => "required",
            "price" => "required|numeric|gt:0",
            "stock" => "required|numeric|gt:0",
            'image' => 'image',
            'pricePromotion' => 'numeric',
        ]);

        $produto = Products::findOrFail($id);
        $produto->setName($request->input('name'));
        $produto->setPrice($request->input('price'));
        $produto->setStock($request->input('stock'));
        $produto->setPricePromotion($request->input('pricePromotion'));
        $produto->setDescription($request->input('description'));

        // if ($request->hasFile('image')) {
        //     $imageName = $produto->getId() . "." . $request->file('image')->extension();
        //     Storage::disk('public')->put(
        //         $imageName,
        //         file_get_contents($request->file('image')->getRealPath())
        //     );
        //     $produto->setImage($imageName);
        // }
        $produto->save();
        return redirect()->route('products.index');
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

    public function CartShow($id)
    {
        $product = Products::findOrFail($id);

        if ($product) {
            $viewData = [];
            $viewData['title'] = "MotoStore : " . $product->name;
            $viewData['subtitle'] = $product->name;
            $viewData['product'] = $product;
            $viewData['stock'] = $product->stock;
            $viewData['image'] = $product->image;
            return view('store.singleProductShow')->with('viewData', $viewData);
        }

        return redirect()->route('store.index');
    }
}
