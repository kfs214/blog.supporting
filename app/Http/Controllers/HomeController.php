<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('verified')->except('discard');
    }


    /**
     * 認証・ログイン系
     */
    public function discard(){
      if(Auth::user()->hasVerifiedEmail()){
        return redirect(route('home'), 303)->with('status', '無効な操作です。');
      }

      Auth::user()->delete();
      session()->flush();

      return redirect(route('register'), 303)->with('status', '操作が完了しました。');
    }

    public function logout(){
      Auth::logout();
      session()->forget('aimed.url');
      return redirect(route('tags'), 303)->with('status', 'ログアウトしました');
    }


    /**
     * 設定系
     */
    public function showSettings(){
      $user = Auth::user();

      return view('settings.frequency', compact('user'));
    }

    public function updateSettings(){
      dd($request->input());
      $user = Auth::user();

      return view('settings.frequency', compact('user'));
    }


    public function showUrlSettings(){
      $user = Auth::user();

      return view('settings.url', compact('user'));
    }

    public function updateUrlSettings(){
      dd($request->input());
      $user = Auth::user();

      return view('settings.url', compact('user'));
    }


    public function showAccountSettings(){
      $user = Auth::user();

      return view('settings.account', compact('user'));
    }

    public function updateAccountSettings(){
      dd($request->input());
      $user = Auth::user();

      return view('settings.account', compact('user'));
    }


    public function showEmailSettings(){
      $user = Auth::user();

      return view('settings.email', compact('user'));
    }

    public function updateEmailSettings(){
      dd($request->input());
      $user = Auth::user();

      return view('settings.email', compact('user'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
