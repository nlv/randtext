<?php

use RotEval\TextRandomizer\TextRandomozer;
//use RotEval\TextRandomizer;

use Illuminate\Http\Request;

function br2nl($str)
{
$str = preg_replace("/(\n|\r)/", "", $str);
return preg_replace("/i", "\r\n", $str);
}

$router->get('/', function (Request $request) use ($router) {


// return response()->json($request->input('text'))->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);

$a = json_encode($request->input('text'),JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
//$a = str_replace("\n","\r",$a);
//print_r($a);
//die();

	//$tRand = new RotEval\TextRandomizer\TextRandomizer($request->input('text'));
	$tRand = new RotEval\TextRandomizer\TextRandomizer($a);
    $tCnt = $tRand->numVariant();

    $cnt = $request->input('count');
    if ($cnt && $cnt > 0) {
      $cnt = min ($cnt, $tCnt);
    } else {
      $cnt = $tCnt;
    }

    $res = [];
	for ($i=0; $i<$cnt; ++$i) {
		$res[] = json_decode($tRand->getText(),JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
//print_r($res);
//die();
	}

  // TODO проверять не наличие строки application/json, а разрешение, согласно протоколу 
    // if ($request->header('Accept') != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
  $accept = explode(',',$request->header('Accept'));      
  $accept = array_map (function ($i) { return explode(';',$i); }, $accept);
  $accept = array_map (function ($i) { return array_shift($i); }, $accept);
 
  if (in_array('application/json', $accept)) {
    return response()->json($res)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
  }
  else {
    $tmp = tempnam(sys_get_temp_dir(),'randtext-');
    $xlsx = Shuchkin\SimpleXLSXGen::fromArray( array_map(function ($i) { return [$i];}, $res ));
    $xlsx->saveAs($tmp); 
    return response()->download($tmp, 'res.xlsx');  
  }
});


