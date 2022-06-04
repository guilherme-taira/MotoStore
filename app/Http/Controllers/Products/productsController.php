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

        $validator = Validator::make($request->all, [
            'name' => 'required|min:5',
            'price' => 'required|numeric|min:1',
            'image' => 'required|file',
            'descrition' => 'required|max:500'
        ]);

        if ($validator->fails()) {
            return redirect('post/create')
                ->withErrors($validator)
                ->withInput();
        }

        $produto = new Products();
        $produto->name = $request->name;
        $produto->price = $request->price;
        $produto->description = $request->description;
        $produto->image = 'image.png';
        $produto->save();

        if ($request->hasFile('image')) {
            $imageName = $produto->getId() . "." . $request->file('image')->extension();
            Storage::disk('public')->put(
                $imageName,
                file_get_contents($request->file('image')->getRealPath())
            );
            $produto->setImage($imageName);
            $produto->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Products::findOrFail($id);

        if ($product) {
            $viewData = [];
            $viewData['title'] = "MotoStore : " . $product->name;
            $viewData['subtitle'] = $product->name;
            $viewData['product'] = $product;
            $viewData['image'] = $product->image;
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
        print_r($id);
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
            'image' => 'image',
        ]);

        $produto = Products::findOrFail($id);
        $produto->setName($request->input('name'));
        $produto->setPrice($request->input('price'));
        $produto->setDescription($request->input('description'));

        if ($request->hasFile('image')) {
            $imageName = $produto->getId() . "." . $request->file('image')->extension();
            Storage::disk('public')->put(
                $imageName,
                file_get_contents($request->file('image')->getRealPath())
            );
            $produto->setImage($imageName);
        }
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
            $viewData['image'] = $product->image;
            return view('store.show')->with('viewData', $viewData);
        }

        return redirect()->route('store.index');
    }
}
