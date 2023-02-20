<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\banner;
use App\Models\banner_autokm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerAutoKmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [];
        $viewData['title'] = "Marketing - Banner";
        $viewData['subtitle'] = "Banner AutoKM";
        $viewData['banners'] = banner_autokm::all();

        return view('marketing.autokm.index',[
            'viewData' => $viewData
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $viewData = [];
        $viewData['title'] = "Banners";
        $viewData['subtitle'] = "Criador de Banner";
        return view('marketing.banners.create_autokm',
        ['viewData' => $viewData,]);
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
            'image' => 'required|file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $banner = new banner_autokm();
        $banner->name = $request->name;
        $banner->image = 'image.png';
        $banner->save();

        if ($request->hasFile('image')) {
            //$imageName = $produto->getId() . "." . $request->file('image')->extension();
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $file->storeAs('bannersAutokm/'.$banner->getId(),$filename,'s3');
            $banner->setImage($filename);
            $banner->save();
        }

        return redirect()->route('banner.index');
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
