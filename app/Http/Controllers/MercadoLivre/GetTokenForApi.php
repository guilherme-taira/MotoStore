<?php

namespace App\Http\Controllers\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Models\token;
use Illuminate\Http\Request;

class GetTokenForApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show(Request $request)
    {
        $token = token::where("user_id", $request->id)->first();
        return response()->json(["token" => $token->access_token]);
    }

    public function getUserID(Request $request){
        $token = token::where("user_id", $request->id)->first();
        return response()->json(["user" => $token->user_id_mercadolivre]);
    }

    public function trataError(Request $request)
    {
        try {
            $dt = json_decode(json_encode($request->data));
            $regex = "/\[(.*?)\]/";
            foreach ($dt->cause as $cause) {
                if (preg_match($regex, $cause->message, $matches)) {
                    unset($matches[0]);
                    $string = implode(",", $matches);
                    $array = explode(",", $string);
                    $json_response = [];
                    foreach ($array as  $value) {
                        array_push($json_response, [$value => "Genêrico"]);
                    }
                    return response()->json($json_response);
                }
           }
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
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
