<?
// include "HomeServerInc/HomeServerApiClient.php";

$cTplIndex = new cTemplate("tpl.index.htm");
$cAutomatesConfigSite = new cAutomates("zpainel_config");
$configSite = $cAutomatesConfigSite->mRetornaDados();
$cTplIndex->mSet("titulo_site_config", $configSite[0]["titulo_site_config"]);
$cTplIndex->mSet("description_site_config", $configSite[0]["description_site_config"]);


require_once 'vendor/autoload.php';

use HomeServerInc\API\HomeServerApiClient;

$client = new HomeServerApiClient('superadministrator@app.com', 'password');


    $response = $client->getSite('a3ef6cba-77db-492b-81f5-1d23012d1617');
    
    $site = json_decode($response, true);
    
    $cTplIndex->mSet("tt_tel_topo",$site['data']['name']);
    $cTplIndex->mSet("tel_topo",$site['data']['phone']['friendly_name']);
    $phone = $site['data']['phone']['friendly_name'];
    //$cTplIndex->mSet("tel_topo","(781) 312 5934");
    
    $cTplIndex->mSet("name",$site['data']['name']);

if ($cUrl->mGetParam(1) === "services-painting"){   
    //OG:IMAGE SEO SERVICES PAINTING
    $cAutomates = new cAutomates("service_images");
    $fotos = $cAutomates->mRetornaDados("","cd_service ='".$cUrl->mGetParam(2)."'");
        for ($i=0; $i < count($fotos); $i++) { 
            $print.= "<meta property = \"og:image\" content=\"https://bostonpaintingsvc.com/".$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_image'],800,3)."\">";
        }
    $cTplIndex->mSet("image", $print);
}

if ($cUrl->mGetParam(1) === "portfolio"){   
    //OG:IMAGE SEO PORTFOLIO
    $cAutomates = new cAutomates("portfolio_images");
    $fotos = $cAutomates->mRetornaDados("","cd_portfolio ='".$cUrl->mGetParam(2)."'");
        for ($i=0; $i < count($fotos); $i++) { 
            $print.= "<meta property = \"og:image\" content=\"https://bostonpaintingsvc.com/".$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_image'],800,3)."\">";
        }
    $cTplIndex->mSet("image", $print);
}
if ($cUrl->mGetParam(1) == "painting"){
    
}
if ($cUrl->mGetParam(1) == ""){   
    //OG:IMAGE SEO INDEX
    $print.= "<meta property = \"og:image\" content=\"https://bostonpaintingsvc.com/demos/construction/images/slider/2.jpg\>";
    $print.= "<meta property = \"og:image\" content=\"https://bostonpaintingsvc.com/demos/construction/images/slider/1.jpg\>";
    $print.= "<meta property = \"og:image\" content=\"https://bostonpaintingsvc.com/demos/construction/images/slider/3.jpg\>";
    $print.= "<meta property = \"og:image\" content=\"https://bostonpaintingsvc.com/demos/construction/images/slider/4.jpg\>";
    $print.= "<meta property = \"og:image\" content=\"https://bostonpaintingsvc.com/images/services/house_painting.jpg\>";
    $print.= "<meta property = \"og:image\" content=\"https://bostonpaintingsvc.com/images/services/painting_company.jpg\>";
    $print.= "<meta property = \"og:image\" content=\"https://bostonpaintingsvc.com/images/services/interior_painting.jpg\>";
    $print.= "<meta property = \"og:image\" content=\"https://bostonpaintingsvc.com/demos/construction/images/services/bottom-trust.jpg\>";

    $cTplIndex->mSet("image", $print);
}

if ($cUrl->mGetParam(1) == "services-painting" && $cUrl->mGetParam(2) == "1" ) {
    $cTplIndex->mSet("titulo_site_config", "Boston Painting Services - Interior Painting. Get Quote Free!");
    $cTplIndex->mSet("description_site_config", "Boston Professional Interior Painting. Call us at ".$phone." for all your painting needs for a free estimate.");
}

if ($cUrl->mGetParam(1) == "services-painting" && $cUrl->mGetParam(2) == "2" ) {
    $cTplIndex->mSet("titulo_site_config", "Boston Painting Services - House Painting. Get Quote Free!");
    $cTplIndex->mSet("description_site_config", "Boston Professional Exterior Painting. Call us at ".$phone." for all your painting needs for a free estimate.");
}
if ($cUrl->mGetParam(1) == "services-painting" && $cUrl->mGetParam(2) == "3" ) {
    $cTplIndex->mSet("titulo_site_config", "Boston Painting Services - Residential Painting. Get Quote Free!");
    $cTplIndex->mSet("description_site_config", "Boston Professional Residential Painting. Call us at ".$phone." for all your painting needs for a free estimate.");
}
if ($cUrl->mGetParam(1) == "services-painting" && $cUrl->mGetParam(2) == "4" ) {
    $cTplIndex->mSet("titulo_site_config", "Boston Painting Services - Commercial Painting. Get Quote Free!");
    $cTplIndex->mSet("description_site_config", "Boston Professional Commercial Painting. Call us at ".$phone." for all your painting needs for a free estimate.");
}

if ($cUrl->mGetParam(1) == "portfolio" && $cUrl->mGetParam(2) == "1" ) {
    $cTplIndex->mSet("titulo_site_config", "Boston Painting Services - Interior Painting Portfolio. See the photos!");
    $cTplIndex->mSet("description_site_config", "Need Ideas for Interior Painting in Your House? Check out the photos on our website.");
}
if ($cUrl->mGetParam(1) == "portfolio" && $cUrl->mGetParam(2) == "2" ) {
    $cTplIndex->mSet("titulo_site_config", "Boston Painting Services - Exterior Painting Portfolio. See the photos!");
    $cTplIndex->mSet("description_site_config", "Need Ideas for Exterior Painting in Your House? Check out the photos on our website.");
}
if ($cUrl->mGetParam(1) == "portfolio" && $cUrl->mGetParam(2) == "3" ) {
    $cTplIndex->mSet("titulo_site_config", "Boston Painting Services - Residential Painting Portfolio. See the photos!");
    $cTplIndex->mSet("description_site_config", "Need Ideas for Residential Painting in Your House? Check out the photos on our website.");
}
if ($cUrl->mGetParam(1) == "portfolio" && $cUrl->mGetParam(2) == "4" ) {
    $cTplIndex->mSet("titulo_site_config", "Boston Painting Services - Commercial Painting Portfolio. See the photos!");
    $cTplIndex->mSet("description_site_config", "Need Ideas for Commercial Painting in Your House? Check out the photos on our website.");
}
if ($cUrl->mGetParam(1) == "portfolio" && $cUrl->mGetParam(2) == "5" ) {
    $cTplIndex->mSet("titulo_site_config", "Boston Painting Services - Industrial Painting Portfolio. See the photos!");
    $cTplIndex->mSet("description_site_config", "Need Ideas for Industrial Painting in Your House? Check out the photos on our website.");
}
if ($cUrl->mGetParam(1) == "are-you-a-painter") {
    $cTplIndex->mSet("titulo_site_config", "Boston Painting Services - Painting Contractors Boston MA");
    $cTplIndex->mSet("description_site_config", "Professional Painting Contractors servicing all clients in the Greater Boston area.Call our expert painters today ".$phone."!");
}


//INTERNAS CITIES
if ($cUrl->mGetParam(1) == "painting") {
    $cAutomatesStates = new cAutomates("states");
    $states = $cAutomatesStates->mRetornaDados("","state_code= '".$cUrl->mGetParam(2)."'");
    $nameState = $states[0]['state_name'];
    

    if ($cUrl->mGetParam(4) == true) {
        $cAutomatesCityName = new cAutomates("cities");
        $CityName = $cAutomatesCityName->mRetornaDados("", "id = ".$cUrl->mGetParam(4));
    }
    
   if ($cUrl->mGetParam(4) == false) {
        $cTplIndex->mSet("titulo_site_config", "Boston Painting Services - House Painters in MASSACHUSETTS! Call Us ". $phone);
        $cTplIndex->mSet("description_site_config", "Professional Painting Contractors servicing all clients in the Boston MASSACHUSETTS. Call our expert painters today ".$phone."!");
    }else{
        $cTplIndex->mSet("titulo_site_config", "Boston Painting Services - House Painters in ".$CityName[0]['city']."! Call Us ". $phone);
        $cTplIndex->mSet("description_site_config", "Professional Painting Contractors servicing all clients in the Boston ".$CityName[0]['city'].". Call our expert painters today ".$phone."!");
    }
    
}







$cTplIndex->mSet("keywords_site_config", $configSite[0]["keywords_site_config"]);
$cTplIndex->mSet("analytics_site_config", $configSite[0]["analytics_site_config"]);
$cTplIndex->mSet("webmasters_site_config", $configSite[0]["webmasters_site_config"]);

$cTplIndex->mSet("data_atual", $cToo->mDiaSemanaBr(date('l')).", ".date('d')." de ".$cToo->mMesExtensoBr(date('F'))." de ".date('Y'));

if(file_exists("css/estrutura.css")){
	$data = date("Ymd", filemtime("css/estrutura.css"));	
	$cTplIndex->mSet("version_estrutura", $data);
}

if(file_exists("css/interna.css")){
	$data = date("Ymd", filemtime("css/interna.css"));	
	$cTplIndex->mSet("version_interna", $data);
}






$cTplIndex->mShow("inicio");

		$cAutomatesCities = new cAutomates("cities");
        $cities = $cAutomatesCities->mRetornaDados("", "state_id = 22", "rand()", "");

        $cAutomatesStates = new cAutomates("states");
        $state = $cAutomatesStates->mRetornaDados("", "id = 22");

        for ($i=0; $i < 6 ; $i++) { 
            $cTplIndex->mSet("link","painting/" . $state[0]['state_code'] . "/". $cUrl->mTrataString($cities[$i]["city"]."-painting-contractors"));
            $cTplIndex->mSet("nm_citie",$cities[$i]['city']);
            $cTplIndex->mShow("while_locations");
        }
        $cTplIndex->mShow("fim_while_locations");
?>