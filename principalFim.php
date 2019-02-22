<?

$cTplIndex->mSet("area", $cUrl->mGetParam(1));

$cAutomatesHome = new cAutomates("home_end");
$home_end = $cAutomatesHome->mRetornaDados("");




$cTplIndex->mSet("about_us",strip_tags($home_end[3]['txt_home_end']));
$cTplIndex->mSet("lk_facebook",strip_tags($home_end[4]['txt_home_end']));

// use HomeServerInc\API\HomeServerApiClient;

// $client = new HomeServerApiClient('superadministrator@app.com', 'password');

// if ($client->auth()) {
//     $response = $client->getSite('49c96f2f-733b-468f-b374-bc3f3116cb34');
    
//     $site = json_decode($response, true)['data'];
    
//     $cTplIndex->mSet("tt_tel_topo",$site['name']);
// 	$cTplIndex->mSet("tel_topo",$site['phone']['friendly_name']);

// }

$cTplIndex->mShow("fim");
?>