<?php

use RotEval\TextRandomizer\TextRandomozer;
//use RotEval\TextRandomizer;

use Illuminate\Http\Request;

$router->get('/', function (Request $request) use ($router) {

//    return response()->json([$request->input('text')]);

	$tRand = new RotEval\TextRandomizer\TextRandomizer($request->input('text'));
    $cnt = $tRand->numVariant();
    $res = [];

	for ($i=0; $i<$cnt; ++$i) {
		$res[] = $tRand->getText();
	}

    return response()->json($res);
});


