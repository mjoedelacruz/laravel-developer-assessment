<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class VerifyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $pin = $request->input('secret');
        $user = $request->input('user_name');
        $userDetails = User::where('user_name',$user)->first();


        if(!$pin){
            return [ "status" => "Error", "message" =>"Invalid Pin."];
        }
        $user =  User::where(['secret'=>$pin , 'user_name' => $user])->first();
        //dd($user);
        if(!$user){
            return [ "status" => "Error", "message" =>"Pin does not match."];
        }
        else if($userDetails->email_verified_at !== null){
            return [ "status" => "Invalid", "message" =>"Already authenticated."];
        }
        $userDetails->email_verified_at = date('Y-m-d H:i:s');
        $userDetails->save();
        return [ "status" => "Success", "message" =>"Pin Verified."];


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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