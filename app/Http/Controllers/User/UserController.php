<?php

namespace App\Http\Controllers\User;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\Response;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
        return $users;
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];
        $this->validate($request, $validation);
        $data = $request->all();
        
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verified_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;
        
        $user = User::create($data);
        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
        return $user;

    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $validation = [
            'email' => '|email|unique:users',
            'password' => '|min:6|confirmed',
            'admin' =>'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER,
        ];

        if($request->has('name')) 
        {
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email) 
        {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if($request->has('password'))
        {
            $user->password = bcrypt($request->password);
        }

        if($request->has('admin'))
        {
            if(!$user->isVERIFIED) 
            {
                return $this->errorReseponse('This is only for verified users, only verified users can modify the admin
                fields', 'code', 409);
            }
            $user->admin = $request->admin;
        }
        if(!$user->isDirty())
        {
            return $this->errorReseponse('you neeed to specify a different value to update', 'code', 422);    
         }  
        $user->save();
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
            $user->delete();
            return $this->showOne($user);
    }
}
