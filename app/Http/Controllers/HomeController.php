<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Frequency;
use App\Url;

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

    public function updateSettings(Request $request){
      $user = Auth::user();

      if($request->create){
        $data = $request->validate([
          'url_id' => 'required|integer',
          'number' => 'required|integer',
          'unit' => 'required|string',
          'enabled' => 'required|array',
          'enabled.*' => 'required|integer',
        ]);

        $data['user_id'] = Auth::id();

        $enabled = $data['enabled'];

        unset($data['enabled']);

        $frequency = new Frequency;

        $frequency->fill($data)->save();

      }elseif($request->update){
        $data = $request->validate([
          'update' => 'required|integer',
          'numbers' => 'required|array',
          'numbers.*' => 'required|integer',
          'unit' => 'required|string',
          'enabled_accounts' => 'required|array',
          'enabled_accounts.*' => 'required|array',
          'enabled_accounts.*.*' => 'required|integer',
        ]);

        $frequency_id = $data['update'];

        unset($data['enabled_accounts']['dummy']);

        $enabled = current($data['enabled_accounts']);
        $data['number'] = current($data['numbers']);

        unset($data['update']);
        unset($data['enabled_accounts']);
        unset($data['numbers']);

        $frequency = Frequency::find($frequency_id);

        $frequency->fill($data)->save();

      }elseif($request->delete){
        $data = $request->validate([
          'delete' => 'required|integer',
        ]);

        $frequency_id = $data['delete'];

        if($user->frequencies()->get(['id'])->pluck('id')->contains($frequency_id)){
          Frequency::destroy($frequency_id);

        }else{
          return redirect(route('settings.frequency'), 303)->with('status', '不正な操作です');

        }
      }

      if(isset($enabled)){
        $frequency->accounts()->sync($enabled);
      }


      return redirect(route('settings.frequency'), 303)->with('status', '更新が完了しました');
    }


    public function showUrlSettings(){
      $user = Auth::user();

      return view('settings.url', compact('user'));
    }

    public function updateUrlSettings(Request $request){
      $user = Auth::user();

      $data = $request->validate([
        'delete' => 'required|integer',
      ]);

      $url_id = $data['delete'];

      if($user->urls->pluck('id')->contains($url_id)){
        Url::destroy($url_id);

        Frequency::where(compact('url_id'))->delete();

      }else{
        return redirect(route('settings.url'), 303)->with('status', '不正な操作です');

      }


      return redirect(route('settings.url'), 303)->with('status', '更新が完了しました');
    }


    public function showAccountSettings(){
      $user = Auth::user();

      return view('settings.account', compact('user'));
    }

    public function updateAccountSettings(Request $request){
      dd($request->input());
      $user = Auth::user();

      return view('settings.account', compact('user'));
    }


    public function showEmailSettings(){
      $user = Auth::user();

      return view('settings.email', compact('user'));
    }

    public function updateEmailSettings(Request $request){
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
