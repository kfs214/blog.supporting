<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\Account;
use App\Email;
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

        $data['user_id'] = $user->id;

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
      }elseif($request->source){
        $month = $request->month;
        $day = $request->day;

        if(strlen($month) == 1){
          $month = '0' . $month;
        }
        if(strlen($day) == 1){
          $day = '0' . $day;
        }

        $request->merge(['date' => "$request->year-$month-$day"]);

        $data = $request->validate([
          'source' => 'required|active_url',
          'date' => 'required|date_format:"Y-m-d"',
          'frequency_id' => 'required|integer',
        ]);

        $frequency = Frequency::find($data['frequency_id']);

        $date = new Carbon($data['date']);

        switch($frequency->unit){
          case 'years':
            $date->subYears($frequency->number);
            break;

          case 'months':
            $date->subMonths($frequency->number);
            break;

          case 'weeks':
            $date->subWeeks($frequency->number);
            break;

          case 'days':
            $date->subDays($frequency->number);
            break;
        }

        session()->flash('source', $data['source']);
        session()->flash('date', $date->toDateString());

        return redirect(route('test'), 303);

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

      if($request->add){
        $data = $request->validate(
          ['add' => 'required|url'],
        );

        $url = new Url;

        $url->url = $data['add'];
        $url->user_id = $user->id;

        $url->save();

      }else{
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

      }

      return redirect(route('settings.url'), 303)->with('status', '更新が完了しました');
    }


    public function showAccountSettings(){
      $user = Auth::user();

      return view('settings.account', compact('user'));
    }

    public function updateAccountSettings(Request $request){
      $user = Auth::user();

      $data = $request->validate([
        'delete' => 'required|integer',
      ]);

      $account_id = $data['delete'];

      if($user->accounts->pluck('id')->contains($account_id)){
        Account::destroy($account_id);

      }else{
        return redirect(route('settings.account'), 303)->with('status', '不正な操作です');

      }

      return redirect(route('settings.account'), 303)->with('status', '更新が完了しました');
    }


    public function showEmailSettings(){
      $user = Auth::user();

      return view('settings.email', compact('user'));
    }

    public function updateEmailSettings(Request $request){
      $user = Auth::user();

      if($request->delete){
        $data = $request->validate([
          'delete' => 'required|integer',
        ]);

        $email_id = $data['delete'];

        if($user->emails->pluck('id')->contains($email_id)){
          Email::destroy($email_id);

        }else{
          return redirect(route('settings.email'), 303)->with('status', '不正な操作です');

        }
      }elseif($request->update){
        $data = $request->validate([
          'update' => 'required|integer',
          'belongs' => 'nullable|array'
        ]);

        $email_id = $data['update'];
        $belongs = $data['belongs'] ?? [];

        foreach($belongs as $belong){
          if(!$user->groups->pluck('id')->contains($belong)){
            return redirect(route('settings.email'), 303)->with('status', '不正な操作です');
          }
        }

        if($user->emails->pluck('id')->contains($email_id)){
          Email::find($email_id)->accounts()->sync($belongs);

        }else{
          return redirect(route('settings.email'), 303)->with('status', '不正な操作です');

        }
      }elseif($request->emails){
        if(!$user->groups->count()){
          $account = new Account;

          $data_to_write = [
            'user_id' => $user->id,
            'type' => 'email',
            'account' => 'default',
          ];

          $account->fill($data_to_write)->save();

          $default_account_id = $account->id;
        }

        if($user->plan == 'free'){
          $data = $request->validate([
            'emails' => 'required|array',
            'emails.0' => 'required|email',
            'emails.*' => 'nullable|email',
          ]);

        }else{
          $data = $request->validate([
            'emails' => 'required|array',
            'emails.0' => 'required|email',
            'emails.*' => 'nullable|email',
            'belongs' => 'required|array',
            'belongs.*' => 'required_with:emails.*|integer'
          ]);

          foreach($data['belongs'] as $key => &$belongs){
            if(!$data['emails'][$key]){
              unset($data['emails'][$key]);
              unset($data['belongs'][$key]);

              continue;

            }

            if($belongs){
              if(!$user->groups->pluck('id')->contains($belongs)){
                return redirect(route('settings.email'), 303)->with('status', '不正な操作です');

              }
            }else{
              $belongs = $default_account_id;

            }
          }

        }

        foreach($data['emails'] as $key => &$email_address){
          if(!$email_address){
            unset($email_address);

            continue;

          }

          $email = new Email;

          $data_to_write = ['email' => $email_address, 'user_id' => $user->id];

          $email->fill($data_to_write)->save();

          if(isset($data['belongs'][$key])){
            $email->accounts()->attach($data['belongs'][$key]);
          }

        }

      }elseif($request->group){
        if($user->plan == 'free'){
          return redirect(route('settings.email'), 303)->with('status', '不正な操作です');

        }

        $data = $request->validate([
          'group' => 'required|string'
        ]);

        $accounts = new Account;

        $data_to_write = [
          'user_id' => $user->id,
          'type' => 'email',
          'account' => $data['group'],
        ];

        $accounts->fill($data_to_write)->save();
      }

      return redirect(route('settings.email'), 303)->with('status', '更新が完了しました');
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
