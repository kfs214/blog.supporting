<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(){
      return view('index');
    }

    public function support(Request $request){
      $array = explode("\r\n\r\n\r\n", $request->contents);
      $array = array_values($array);
      $contents = [];

      foreach($array as $content){
        $rows = explode("\r\n", $content);
        $rows = array_values($rows);
        $contents[] = $rows;
      }

      return view('index', ['contents' => $contents, 'year' => $request->year]);
    }
}
