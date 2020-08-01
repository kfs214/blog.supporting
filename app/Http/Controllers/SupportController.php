<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(){
      return view('index');
    }

    public function support(Request $request){
      session()->flash('contents', $request->contents);
      session()->flash('year', $request->year);

      $contents = str_replace(["\r\n", "\r"], "\n", $request->contents); // \nで統制
      $contents = explode("\n\n\n", $contents); //空白2行で次のコンテンツ

      //各コンテンツについて処理
      foreach($contents as $content_no => &$content){
        // 最後の改行を除去、$date保存
        $content = trim($content);
        $date = trim(strrchr($content, "\n"));
        $content = str_replace($date, '', $content);

        // parについて処理
        $pars = explode("\n\n", $content);  //空白1行でp

        $titles[] = $pars[0];

        $content_class = $content_no ? '' : ' first';

        $temp_content = '<div id="' . $content_no . '" class="items' . $content_class . '">' . "\n";

        // 各parについて処理
        foreach($pars as $par_no => $par){
          // $pars[0]にはタイトルが入っているのでcontinue;
          if(!$par_no){
            continue;
          }

          // 第1parならclass="top"
          $par_class = $par_no == 1 ? ' class="top"' : '';

          //#5, #6を<h5><h6>タグに変換。
          while(true){
            $heading = strstr($par, "\n", true);

            if(!preg_match("/(#5|#6)/", $heading)){
              break;
            }

            if(mb_strpos($heading, '#5') !== false){
              $temp_content .= preg_replace("/#5\ */", "<h5$par_class>", $heading) . "</h5>\n";
            }elseif(mb_strpos($heading, '#6') !== false){
              $temp_content .= preg_replace("/#6\ */", "<h6$par_class>", $heading) . "</h6>\n";
            }

            $par = trim(strstr($par, "\n"));
          }

          // nl2br
          $temp_content .= "<p>\n" . str_replace("\n", "<br>\n", trim($par)) . "\n</p>\n\n";
        }

        $content = $temp_content . '</div><div class="date">' . $request->year . $date . "</div>\n\n\n";
      }

      unset($content);

      // コード生成
      // まず見出しリンク
      // 「抜粋」用の文字列も
      $code = '';
      $headline = '';

      foreach ($titles as $content_no => $title) {
        $code .= '<a href="#' . $content_no . '" class="eyecatch">' . $title . "</a>";
        $headline .= $title . '／';
      }
      $code .= "\n</div>\n\n<!--more-->\n\n\n";

      // 本文
      foreach($contents as $content){
        $code .= $content;
      }

      return view('index', compact('code', 'headline'));
    }

}
