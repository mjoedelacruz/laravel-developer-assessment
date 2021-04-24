<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator;
use Hash;
use App\Events\shouldSendEmail;
use App\Mail\RegistrationEmail;
class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return [];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $userDetails = User::make($inputs);
        //$image_path = "C:\Users\Kaji Lao\Pictures";
        // $role = Role::create(['name' => 'writer']);
        // $permission = Permission::create(['name' => 'edit articles']);

        $v = Validator::make($inputs, [
            'name' => 'required|max:255|min:4',
            'user_name' => 'required|max:20|min:4|unique:users',
            'email' => 'sometimes|required|email|unique:users',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:width=256,height=256',
        ]);

        if($v->fails())
        {
            return response()->json($v->errors(), 422);
        }

        $secret = rand(100000,999999);
        $userDetails->password = Hash::make($userDetails->password);
        $userDetails->secret = $secret;

        if ($userDetails->save()){

            try{
                $email = new RegistrationEmail($userDetails);
                event(new shouldSendEmail($email->build()));
            }
            catch(Exception $e){
                return $e->getMessage();
            }
            //shouldSendEmail::dispatch($email->build());
            return [
                'success'=> true,
                'status'=> 'Successfully Saved'
            ];
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
        return [];
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