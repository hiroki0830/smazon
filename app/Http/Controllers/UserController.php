<?php

namespace App\Http\Controllers;

use App\User; //Userのクラスを仕様するために、useを持ってくる、パスで繋ぐことが可能
 use Illuminate\Support\Facades\Auth;//Authのクラスを使用します！
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function mypage()
    {
        $user = Auth::user();
        //$userはAuthという認証データのクラスから、
        //user()の情報を取得してくれている

        return view('users.mypage', compact('user'));
       //第一引数である、users.mypageにページを返す
       //第二引数である、compact関数は、変数を第一引数のページに引き渡すという意味
       //compact('user')は上記の$userと同じ！
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $user = Auth::user();
        //$userは、Authクラスのuser()情報を取得した変数

        return view('users.edit', compact('user'));
        //第一引数である、users.editに、compact関数の('user')=$userの情報を引き渡す
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = Auth::user();

        $user->name = $request->input('name')? $request->input('name') : $user->name;
        $user->email =$request->input('email')? $request->input('email') : $user->email;
        $user->postal_code = $request->input('postal_code')? $request->inputo('postal_code') : $user->postal_code;
        $user->address = $request->input('address')? $request->input('address') : $user->address;
        $user->phone = $request->input('phone')? $request->input('phone') : $user->phone;
        $user->update();

        return redirect()->route('mypage');

    }

    public function edit_address()
    {
        $user = Auth::user();

        return view('users.edit_address', compact('user'));
        //compact関数は本当に連想配列なのか？２つ意味があった
    }

}
