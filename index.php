<?php

session_start();

//require_once('/home1/eduardofirst/sites/bostonpaintingsvc.com/owa/owa_php.php');
        
//$owa = new owa_php();
// Set the site id you want to track
//$owa->setSiteId('9903a8ed540b61ab5ad6a71309ec12a2');
// Uncomment the next line to set your page title
//$owa->setPageTitle('somepagetitle');
// Set other page properties
//$owa->setProperty('foo', 'bar');
//$owa->trackPageView();


include $_SERVER['DOCUMENT_ROOT']."/zpainel/class/inc.template.php";
include $_SERVER['DOCUMENT_ROOT']."/zpainel/class/inc.conexao.php";
include $_SERVER['DOCUMENT_ROOT']."/zpainel/class/inc.tools.php";
include $_SERVER['DOCUMENT_ROOT']."/zpainel/class/inc.url.php";
include $_SERVER['DOCUMENT_ROOT']."/zpainel/class/inc.gd.php";
include $_SERVER['DOCUMENT_ROOT']."/zpainel/class/automates/inc.automates.php";

$cToo = new cTools;
$cUrl = new cUrl;
$cCon = new cConexao;

$cGd = new cGd;

$caminhoFotos = "fotos/";

switch ($cUrl->mGetParam(1)) {

	/**************************
        ABOUT-US
    **************************/
    case "about-us":
        include "principalInicio.php";
        $cTpl = new cTemplate("tpl.about-us.htm");
        $cAutomates = new cAutomates("about");


        $about = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
        
        $cTpl->mSet("tt_about", $about[0]['tt_about']);
        $cTpl->mSet("sub_about", $about[0]['sub_about']);
        $cTpl->mSet("tt_about_middle", $about[0]['tt_about_middle']);
        $cTpl->mSet("ds_about", nl2br($about[0]['ds_about']));
        $cTpl->mShow("inicio");
        
        // $cAutomates = new cAutomates("pacotes");
        // $pacote = $cAutomates->mRetornaDados();
        
        // for ($i=0; $i < count($pacote); $i++) { 
        //     $cTpl->mShow("while");
            
        //     $cAutomates = new cAutomates("pacotes_fotos");
        //     $fotos = $cAutomates->mRetornaDados("","cd_pacote ='".$pacote[$i]['cd_pacote']."'");
        //     $cTpl->mSet("link","pacotes/".$pacote[$i]['cd_pacote']."/".$cUrl->mTrataString($pacote[$i]['nm_pacote']).".html");
            
        //     if(count($fotos) > 0) {
        //         $cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],360,2));
        //         $cTpl->mShow("foto");
        //     }
            
        //     $cTpl->mSet("nome",$pacote[$i]['nm_pacote']);
        //     $cTpl->mSet("descricao",$cAutomates->mBreveDescricao($pacote[$i]['ds_pacote'],150));
        //     $cTpl->mShow("dados");
        // }
        $cTpl->mShow("fim");

        include "principalFim.php";
        break;



     /**************************
        LEADSEND MANUAL
    **************************/



    case "leads":
        
        
        if ($cUrl->mGetParam(2) == "send") {
        
                   
                if ($_POST['name'] != "") {

                    

                        //GRAVA NO BANCO
                   // $cAutomates = new cAutomates("contato");
                   

                    $dados["dt_contato"] = "'" . date("Y-m-d H:i:s") . "'";
                    $dados["name"] = "'" . $_POST["name"] . "'";
                    $dados["phone "] = "'" . $_POST["phone"] . "'";
                    $dados["address "] = "'" . $_POST["address"] . "'";
                    $dados["email"] = "'" . $_POST["email"] . "'";
                    $_POST["request"] = nl2br($_POST["request"]);
                    
                    //$cAutomates->mAtualizaDados($dados);

                        //MONTA EMAIL
                    $cTpl = new cTemplate("tpl.email.htm");
                    $cTpl->mSet("site", "BostonPaintingSvc");
                    $cTpl->mSet("pagina", "NewLead");
                    $cTpl->mSet("hora", date("H:i"));
                    $cTpl->mSet("dia", date("d/m/Y"));

                    $conteudo .= "<p><strong>Name</strong><br />" . $_POST["name"] . "</p>";
                    $conteudo .= "<p><strong>Phone</strong><br />" . $_POST["phone"] . "</p>";
                    $conteudo .= "<p><strong>Email</strong><br />" . $_POST["email"] . "</p>";
                    $conteudo .= "<p><strong>Address</strong><br />" . $_POST["address"] . "</p>";
                    $conteudo .= "<p><strong>Services:</strong><br />";
                     if(isset($_POST["Interior_Painting_Services"])){
                        $conteudo .= " | Interior_Painting_Services | ";
                    } 
                     if(isset($_POST["Exterior_Painting_Services"])){
                        $conteudo .= " | Exterior_Painting_Services | ";
                    } 
                    if(isset($_POST["Residential"])){
                        $conteudo .= " | Residential | ";
                    } 
                    if(isset($_POST["Commercial"])){
                        $conteudo .= " | Commercial | ";
                    } 

                    $conteudo .= "<p><strong>Request</strong><br />" . $_POST["request"] . "</p>";


                    $cTpl->mSet("conteudo", $conteudo);
                    $body = $cTpl->mShow("", 1);

                    require_once('zpainel/class/inc.phpmailer.php');
                    $PHPMailer = new PHPMailer();

                    $PHPMailer->IsSMTP();
                    $PHPMailer->CharSet = 'UTF-8';
                    $PHPMailer->SMTPDebug = 1;
                        $PHPMailer->Port = 25; //Indica a porta de conexo para a sada de e-mails
                        $PHPMailer->Host = 'mail.bostonpaintingsvc.com'; //smtp.dominio.com.br
                        $PHPMailer->SMTPSecure = "tls";
                        //$PHPMailer->SMTPAuth = true; //define se haver ou no autenticao no SMTP
                        //$PHPMailer->Username = 'contact@bostonpaintingsvc.com'; //Informe o e-mai o completo
                        //$PHPMailer->Password = '2cuKayzX/DT'; //Senha da caixa postal
                        $PHPMailer->FromName = 'Leads Boston'; //Nome que ser exibido para o destinatrio
                        $PHPMailer->From = 'contact@bostonpaintingsvc.com'; //Obrigatrio ser a mesma caixa postal indicada em "username"
                        $PHPMailer->Subject = "Boston Painting SVC NEWLEAD";
                        //$cAutomates = new cAutomates("contato_dados");
                        //$faleconosco = $cAutomates->mRetornaDados();
                        $message_success = 'Sua mensagem foi enviada com <strong>sucesso</strong>.';

                       
                        $PHPMailer->AddAddress("lucaszaib@gmail.com");
                        $PHPMailer->AddAddress("eduardofirst@gmail.com");
                        $PHPMailer->AddAddress("marcioelias@gmail.com");
                       
                        
                        $PHPMailer->Subject = "Boston Painting SVC";
                        

                        $PHPMailer->Body = $body;
                        $PHPMailer->AltBody = $body;
                        $PHPMailer->WordWrap = 50;

                        if (!$PHPMailer->Send()) {
                            echo '{ "alert": "error", "message": "Error: ' . $PHPMailer->ErrorInfo . '" }';
                        } else {
                         echo '{ "alert": "success", "message": "Thank your for contact us. We will return as soon as possible!" }';
                     }
                 } 
            }
        
         

         else {
            $cTpl = new cTemplate("tpl.leadsend.htm");
            
           
            $cTpl->mShow("inicio");
            

            
            $cTpl->mShow("fim");
        
        }
       
        break;




     /**************************
        CITIES
    **************************/
    case "painting":
        include "principalInicio.php";
        
        if ($cUrl->mGetParam(3)) {
        
            

            $cTpl = new cTemplate("tpl.cities-int.htm");
            
            $cAutomatesStates = new cAutomates("states");
            $states = $cAutomatesStates->mRetornaDados("","state_code= '".$cUrl->mGetParam(2)."'");

            

            if ($cUrl->mGetParam(4) == true) {
                $cAutomates = new cAutomates("cities");
                $cities = $cAutomates->mRetornaDados("","id = '".$cUrl->mGetParam(4)."'");
            }
            
            //$cTpl->mSet("nm_citie", $states[0]['state_name']);
            if ($cUrl->mGetParam(4) == "22" OR $cUrl->mGetParam(4) == false) {
                $cTpl->mSet("nm_citie", "Massachusetts");
            }else{
                $cTpl->mSet("nm_citie", $cities[0]['city']);
            }
            

            $cTpl->mShow("inicio");
             $cAutomatesTestimony = new cAutomates("testimony");
                $testimony = $cAutomatesTestimony->mRetornaDados("","","cd_testimony DESC","");

                for ($i=0; $i < count($testimony); $i++) { 
                    $cTpl->mSet("nm_testimony",$testimony[$i]['nm_testimony']);
                    $cTpl->mSet("ds_testimony",$testimony[$i]['ds_testimony']);
                    $cTpl->mShow("while_testimony");
                }
                $cTpl->mShow("while_testimony_fim"); 

             $cAutomatesImg = new cAutomates("portfolio_images");
                $imgs = $cAutomatesImg->mRetornaDados("",""," rand()");

                $cAutomatesPortfolio = new cAutomates("portfolio");
                $portfolio = $cAutomatesPortfolio->mRetornaDados("");
                for ($i=0; $i < 8 ; $i++) { 
                    $cTpl->mSet("tt_image",$imgs[$i]['tt_image']);
                    $cTpl->mSet("nm_citie",$imgs[$i]['nm_citie']);
                    $cTpl->mSet("nm_image",$caminhoFotos.$imgs[$i]['nm_image']);
                    
                    for ($j=0; $j < count($portfolio) ; $j++) { 
                        if ($portfolio[$j]['cd_portfolio']==$imgs[$i]['cd_portfolio']) {
                            $cTpl->mSet("lk_portfolio","../../../portfolio/".$imgs[$i]['cd_portfolio']."/".$cUrl->mTrataString($portfolio[$j]["sub_portfolio"]));
                        }
                    }
                    $cTpl->mShow("inspiration_start");
                }
                $cTpl->mShow("inspiration_end");


            // $cAutomates = new cAutomates("portfolio_images");
            // $fotos = $cAutomates->mRetornaDados("","cd_portfolio ='".$cUrl->mGetParam(2)."'");
            
            //     if (count($fotos) > 0) {
                    
            //         $cTpl->mShow("foto");
            //         for ($i=0; $i < count($fotos); $i++) { 
            //             $cTpl->mSet("tt_image",$fotos[$i]['tt_image']);
            //             $cTpl->mSet("nm_citie",$fotos[$i]['nm_citie']);
            //             $cTpl->mSet("thumb",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_image'],91,2));
            //             $cTpl->mSet("image",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_image'],800,3));
            //             $cTpl->mShow("while");              
            //         }
            //         $cTpl->mShow("fim_while");
            //     }

            // $cAutomatesOthers = new cAutomates("portfolio");
            // $portfolioAll = $cAutomatesOthers->mRetornaDados("");

            //     for ($i=0; $i < count($portfolioAll) ; $i++) { 
            //         $cTpl->mSet("tt_portfolio",$portfolioAll[$i]['tt_portfolio']);
            //         $cTpl->mSet("link","portfolio/" . $portfolioAll[$i]['cd_portfolio'] . "/". $cUrl->mTrataString($portfolioAll[$i]["sub_portfolio"]));
            //         $cTpl->mShow("while_port");
            //     }
            //     $cTpl->mShow("fim_while_port");




               


            $cTpl->mShow("fim");

        } else {
            $cTpl = new cTemplate("tpl.services.htm");
            
            $cAutomates = new cAutomates("service");

            $cTpl->mShow("inicio");
            
            // $cAutomates = new cAutomates("pacotes");
            // $pacote = $cAutomates->mRetornaDados();
            
            // for ($i=0; $i < count($pacote); $i++) { 
            //     $cTpl->mShow("while");
                
            //     $cAutomates = new cAutomates("pacotes_fotos");
            //     $fotos = $cAutomates->mRetornaDados("","cd_pacote ='".$pacote[$i]['cd_pacote']."'");
            //     $cTpl->mSet("link","pacotes/".$pacote[$i]['cd_pacote']."/".$cUrl->mTrataString($pacote[$i]['nm_pacote']).".html");
                
            //     if(count($fotos) > 0) {
            //         $cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],360,2));
            //         $cTpl->mShow("foto");
            //     }
                
            //     $cTpl->mSet("nome",$pacote[$i]['nm_pacote']);
            //     $cTpl->mSet("descricao",$cAutomates->mBreveDescricao($pacote[$i]['ds_pacote'],150));
            //     $cTpl->mShow("dados");
            // }
            
            $cTpl->mShow("fim");
        
        }
        include "principalFim.php";
        break;

    /**************************
        PORTFOLIO
    **************************/
    case "portfolio":
        include "principalInicio.php";
        
        if ($cUrl->mGetParam(2)) {
        
            $cTpl = new cTemplate("tpl.portfolio-int.htm");
            
            $cAutomates = new cAutomates("portfolio");
            $portfolio = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
            
            $cTpl->mSet("tt_portfolio", $portfolio[0]['tt_portfolio']);
            $cTpl->mSet("sub_portfolio", $portfolio[0]['sub_portfolio']);
            $cTpl->mSet("tt_portfolio_middle", $portfolio[0]['tt_portfolio_middle']);
            $cTpl->mSet("ds_portfolio_short", nl2br($portfolio[0]['ds_portfolio_short']));
            $cTpl->mSet("ds_portfolio", nl2br($portfolio[0]['ds_portfolio']));

            $cTpl->mShow("inicio");
                        
            $cAutomates = new cAutomates("portfolio_images");
            $fotos = $cAutomates->mRetornaDados("","cd_portfolio ='".$cUrl->mGetParam(2)."'");
            
                if (count($fotos) > 0) {
                    
                    $cTpl->mShow("foto");
                    for ($i=0; $i < count($fotos); $i++) { 
                        $cTpl->mSet("tt_image",$fotos[$i]['tt_image']);
                        $cTpl->mSet("nm_citie",$fotos[$i]['nm_citie']);
                        $cTpl->mSet("thumb",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_image'],91,2));
                        $cTpl->mSet("image",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_image'],800,3));
                        $cTpl->mShow("while");              
                    }
                    $cTpl->mShow("fim_while");
                }

            $cAutomatesOthers = new cAutomates("portfolio");
            $portfolioAll = $cAutomatesOthers->mRetornaDados("");

                for ($i=0; $i < count($portfolioAll) ; $i++) { 
                    $cTpl->mSet("tt_portfolio",$portfolioAll[$i]['tt_portfolio']);
                    $cTpl->mSet("link","portfolio/" . $portfolioAll[$i]['cd_portfolio'] . "/". $cUrl->mTrataString($portfolioAll[$i]["sub_portfolio"]));
                    $cTpl->mShow("while_port");
                }
                $cTpl->mShow("fim_while_port");


            $cTpl->mShow("fim");
        } else {
            $cTpl = new cTemplate("tpl.services.htm");
            
            $cAutomates = new cAutomates("service");

            $cTpl->mShow("inicio");
 
            
            $cTpl->mShow("fim");
        
        }
        include "principalFim.php";
        break;

    /**************************
        SERVICES
    **************************/
    case "services-painting":
        
        
        if ($cUrl->mGetParam(2)) {
            include "principalInicio.php";
            $cTpl = new cTemplate("tpl.service-int.htm");
            
            $cAutomates = new cAutomates("service");
            $service = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
            
            $cTpl->mSet("tt_service", $service[0]['tt_service']);
            $cTpl->mSet("sub_service", $service[0]['sub_service']);
            $cTpl->mSet("tt_service_middle", $service[0]['tt_service_middle']);
            $cTpl->mSet("ds_service_short", nl2br($service[0]['ds_service_short']));
            $cTpl->mSet("ds_service", nl2br($service[0]['ds_service']));

            $cTpl->mShow("inicio");
                        
            $cAutomates = new cAutomates("service_images");
            $fotos = $cAutomates->mRetornaDados("","cd_service ='".$cUrl->mGetParam(2)."'");
            
            if (count($fotos) > 0) {
                
                $cTpl->mShow("foto");
                for ($i=0; $i < count($fotos); $i++) { 
                    $cTpl->mSet("tt_image",$fotos[$i]['tt_image']);
                    $cTpl->mSet("thumb",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_image'],91,2));
                    $cTpl->mSet("image",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_image'],800,3));
                    $cTpl->mShow("while");              
                }
                $cTpl->mShow("fim_while");
            }       
            $cTpl->mShow("fim");
            include "principalFim.php";
        } else {
            
            $cTpl = new cTemplate("tpl.service.htm");
            $cTpl->mShow("inicio");
            $cTpl->mShow("fim");
        
        }
        
        break;


    /**************************
        QUIZ
    **************************/
    case "next":
        
        
        if ($cUrl->mGetParam(2)) {
            include "principalInicio.php";
            $cTpl = new cTemplate("tpl.service-int.htm");
            
            $cAutomates = new cAutomates("service");
            $service = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
            
            $cTpl->mSet("tt_service", $service[0]['tt_service']);
            $cTpl->mSet("sub_service", $service[0]['sub_service']);
            $cTpl->mSet("tt_service_middle", $service[0]['tt_service_middle']);
            $cTpl->mSet("ds_service_short", nl2br($service[0]['ds_service_short']));
            $cTpl->mSet("ds_service", nl2br($service[0]['ds_service']));

            $cTpl->mShow("inicio");
                        
            $cAutomates = new cAutomates("service_images");
            $fotos = $cAutomates->mRetornaDados("","cd_service ='".$cUrl->mGetParam(2)."'");
            
            if (count($fotos) > 0) {
                
                $cTpl->mShow("foto");
                for ($i=0; $i < count($fotos); $i++) { 
                    $cTpl->mSet("tt_image",$fotos[$i]['tt_image']);
                    $cTpl->mSet("thumb",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_image'],91,2));
                    $cTpl->mSet("image",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_image'],800,3));
                    $cTpl->mShow("while");              
                }
                $cTpl->mShow("fim_while");
            }       
            $cTpl->mShow("fim");
            include "principalFim.php";
        } else {
            $cTpl = new cTemplate("tpl.service.htm");

                     
            include "api.php";
            if ($client->auth()) {
                $response = $client->getSite('a3ef6cba-77db-492b-81f5-1d23012d1617');
                //$response = $client->getSite('aa219cca-4c4d-47d0-a275-05b53f29d8c4');
                
                $site = json_decode($response, true);
                
                $cTpl->mSet("tt_tel_topo",$site['data']['name']);
                $cTpl->mSet("tel_topo",$site['data']['phone']['friendly_name']);
                //$cTpl->mSet("tel_topo","(781) 312 5934");

                $cTpl->mSet("name",$site['data']['name']);

            }
      
            $cTpl->mShow("inicio");
            
       
            $cTpl->mShow("fim");
        
        }
        
        break;

        case "services":
             include "api.php";
            if ($cUrl->mGetParam(2)) {
                if ($client->auth()) {
                    echo $client->getServices($cUrl->mGetParam(2));
                }
            }
        break;

        case "service":
             include "api.php";
            if ($cUrl->mGetParam(2)) {
                if ($client->auth()) {
                    echo $client->getService($cUrl->mGetParam(2));
                }
            }
        break;

        case "question":
            include "api.php";
            if ($cUrl->mGetParam(2)) {
                if ($client->auth()) {
                    echo $client->getQuestion($cUrl->mGetParam(2));
                }
            }
        break;

        case "quiz":
            include "api.php";
            if ($cUrl->mGetParam(2)) {
                if ($client->auth()) {
                    echo $client->getQuiz($cUrl->mGetParam(2));
                }
            }
        break;

        case "site":
            include "api.php";
            if ($cUrl->mGetParam(2)) {
                if ($client->auth()) {
                    echo $client->getSite($cUrl->mGetParam(2));
                }
            }
        break;


        /**************************
        CONTACT
        **************************/
        case "contact":
            
            if ($cUrl->mGetParam(2) == "send") {
                include "api.php";
                $res = json_decode($client->setContact()); 
                switch ($res->status) {
                    case 'fail':
                        $old = $res->data->old;
                        $errors = $res->data->errors;
                        break;
                    
                    case 'error':
                        $old = $res->data->old;
                        $errors = ['message' => 'Oops an error ocour...'];
                        echo '{ "alert": "error", "message": "'.$errors.'" }';
                        break;
                    default:
                        echo '{ "alert": "success", "message": "Thank your for contact us. We will return as soon as possible!" }';

                        break;
                }
                 

            } else {
                error_reporting(0);
                include "principalInicio.php";
                $cTpl = new cTemplate("tpl.contact.htm");
                $cTpl->mSet("tt_contact", "Contact");
                $cTpl->mSet("sub_contact", "Get a Free Estimate Now");
            
                include "api.php";
                if ($client->auth()) {
                    $response = $client->getSite('a3ef6cba-77db-492b-81f5-1d23012d1617');
                   
                    $site = json_decode($response, true);
                    
                    $cTpl->mSet("tt_tel_topo",$site['data']['name']);
                    $cTpl->mSet("tel_topo",$site['data']['phone']['friendly_name']);
                    //$cTpl->mSet("tel_topo","(781) 312 5934");
                    $cTpl->mSet("name",$site['data']['name']);
                   
                    $cTpl->mSet("state",$site['data']['city']['state']['state_code']." ".$site['data']['city']['state']['state_name']);
                    $cTpl->mSet("city",$site['data']['city']['city']." ". $site['data']['city']['county']);

                }

                $cTpl->mShow("inicio");
                
                $cTpl->mShow("fim");

                include "principalFim.php";
            }
            break;

        /**************************
        ARE YOU A PAINTER
        **************************/
        case "are-you-a-painter":
            if ($cUrl->mGetParam(2) == "send") {
                include "api.php";
                $res = $client->setContractor(); 
                switch ($res->status) {
                    case 'fail':
                        $old = $res->data->old;
                        $errors = $res->data->errors;
                        break;
                    
                    case 'error':
                        $old = $res->data->old;
                        $errors = ['message' => 'Oops an error ocour...'];
                        echo '{ "alert": "error", "message": "'.$errors.'" }';
                        break;
                    default:
                        echo '{ "alert": "success", "message": "Thank your for contact us. We will return as soon as possible!" }';

                        break;
                }
                 

            } else {
                //error_reporting(0);
                include "principalInicio.php";
                 include "api.php";
                if ($client->auth()) {
                
                $cTpl = new cTemplate("tpl.painter.htm");
                $cTpl->mSet("tt_contact", "Contact");
                $cTpl->mSet("sub_contact", "Get a Free Estimate Now");
                

                $cTpl->mShow("inicio");
               
               
                $response = $client->getSite('a3ef6cba-77db-492b-81f5-1d23012d1617');   
                $categories = json_decode($response, true);

                for ($i=0; $i <count($categories['data']['categories']) ; $i++) { 
                    $cTpl->mSet("name",$categories['data']['categories'][$i]['category']);
                    $cTpl->mSet("value",$categories['data']['categories'][$i]['uuid']);
                     $cTpl->mShow("categories_start");
                }
                  $cTpl->mShow("end");   
                

                $cTpl->mShow("fim");
            }
                include "principalFim.php";
            }
        break;

        case "submit-lead":
            include "api.php";
            if ($cUrl->mGetParam(2)) {
                if ($client->auth()) {
                    echo $client->setLead($cUrl->mGetParam(2));
                }
            }
        break;


        

     /**************************
            SITEMAP GLOBAL
        **************************/
        case "sitemap":
            
            // if ($cUrl->mGetParam(5)) {
            
            //     $cTpl = new cTemplate("tpl.sitemap-int.htm");
                
            //     $cAutomates = new cAutomates("cities");
            //     $cities = $cAutomates->mRetornaDados("","state_id=".$cUrl->mGetParam(4),"");  

            //     $cTpl->mSet("nm_citie",$cities[0]['country']);
            //     $cTpl->mShow("inicio");

                
            //     $consulta = $cCon->mQuery("SELECT * FROM `cities` WHERE `nm_citie` LIKE '".$cUrl->mGetParam(2)."%'");

            //     while ($dados = $cCon->mFetchObject($consulta)) {
                            
            //             $cTpl->mSet("nm_citie",$dados->nm_citie." contractors");
            //             $cTpl->mSet("cd_citie","A/".$dados->cd_citie. "/". $cUrl->mTrataString($dados->nm_citie."-painting"));
                            
            //               $cTpl->mShow("citie_a");
            //             }
            //            $cTpl->mShow("fim_citie_a"); 
                
                
                   
            //     $cTpl->mShow("fim");




            // } else

                if ($cUrl->mGetParam(4)) {
            
                $cTpl = new cTemplate("tpl.sitemap-int-global.htm"); 

                $cTpl->mSet("city",$cUrl->mGetParam(3));
                $city = $cUrl->mGetParam(3);
                $cTpl->mSet("state_id",$cUrl->mGetParam(4));
                $cTpl->mSet("end_url",$cUrl->mGetParam(5));

                $cTpl->mShow("inicio");

               //  $cAutomates = new cAutomates("cities");
               //  $cities = $cAutomates->mRetornaDados("","state_id=".$cUrl->mGetParam(4).""); 
                
               //  for ($i=0; $i < count($cities) ; $i++) { 
               //      $cTpl->mSet("nm_citie",$cities[$i]['city']." ".$cities[$i]['county']);
               //      $cTpl->mSet("cd_citie",$cities[$i]['state_id']);
               //       $cTpl->mShow("citie_a");
               //  }
               // $cTpl->mShow("fim_citie_a"); 


                $consulta = $cCon->mQuery("
                    SELECT * FROM `cities` INNER JOIN states ON ( cities.state_id = states.id ) 
                                    WHERE  `state_id` = ".$cUrl->mGetParam(4)." AND `city` LIKE '".$cUrl->mGetParam(2)."%'
                                     "

                );
                $cAutomatesCityName = new cAutomates("cities");
                while ($dados = $cCon->mFetchObject($consulta)) {
                            
                            $cTpl->mSet("nm_citie",$dados->city." - ".$dados->county." contractors");

                            $CityName = $cAutomatesCityName->mRetornaDados("", "city LIKE '".$dados->city."' AND county LIKE '".$dados->county."'"  );
                            $cTpl->mSet("link",$dados->state_code. "/". $dados->county."-".$dados->city."-painters/".$CityName[0]['id']);
                            $cTpl->mShow("citie_a");
                        }
                       $cTpl->mShow("fim_citie_a"); 
                
                
                   
                $cTpl->mShow("fim");




            } elseif ($cUrl->mGetParam(2)) {
            
                
                $cTpl = new cTemplate("tpl.sitemap-letter.htm");
                
                $cTpl->mShow("inicio");

                $consulta = $cCon->mQuery("SELECT * FROM `states` WHERE `state_name` LIKE '".$cUrl->mGetParam(2)."%'");
                        
                        while ($dados = $cCon->mFetchObject($consulta)) {
                            
                        $cTpl->mSet("nm_citie",$dados->state_code." - ".$dados->state_name." painting");
                        $cTpl->mSet("cd_citie","A/".$dados->state_code. "/".$dados->id. "/". $cUrl->mTrataString($dados->state_name."-painting"));
                            
                          $cTpl->mShow("citie_letter");
                        }
                        $cTpl->mShow("fim_citie_letter");
                   
                $cTpl->mShow("fim");


            } else {
                $cTpl = new cTemplate("tpl.sitemap-global.htm");
                $cTpl->mShow("inicio");
                $cAutomates = new cAutomates("states");
                $states = $cAutomates->mRetornaDados("","state_name = 'Massachusetts'"," state_name  ASC");

                for ($i=0; $i < count($states); $i++) { 
                    $cTpl->mSet("nm_citie",$states[$i]['state_code']." ".$states[$i]['state_name']);
                    $cTpl->mSet("cd_citie","A/".$states[$i]['state_name']."/".$states[$i]['id']);
                    $cTpl->mShow("citie_a");
                }
                $cTpl->mShow("fim_citie_a");
            
            }
            break;




    /**************************
      P�GINA INICIAL
    ***************************/
    default:
        include "principalInicio.php";

        $cTpl = new cTemplate("tpl.principal.htm");

        

        $cTpl->mShow("inicio");

        $cAutomatesTestimony = new cAutomates("testimony");
        $testimony = $cAutomatesTestimony->mRetornaDados("",""," rand()","4");

        for ($i=0; $i < count($testimony); $i++) { 
            $cTpl->mSet("nm_testimony",$testimony[$i]['nm_testimony']);
            $cTpl->mSet("ds_testimony",$testimony[$i]['ds_testimony']);
            $cTpl->mShow("while_testimony");
        }
        $cTpl->mShow("while_testimony_fim");

        $cAutomatesService = new cAutomates("home_service");
        $service = $cAutomatesService->mRetornaDados();

        for ($i=3; $i < count($service); $i++) { 
            $cTpl->mSet("tt_home_service",$service[$i]['tt_home_service']);
            $cTpl->mSet("ds_home_service",$service[$i]['ds_home_service']);
            $cTpl->mShow("while_service");
        }
        $cTpl->mShow("while_service_fim");

        



        $cAutomatesImg = new cAutomates("portfolio_images");
        $imgs = $cAutomatesImg->mRetornaDados("",""," rand()");

        $cAutomatesPortfolio = new cAutomates("portfolio");
        $portfolio = $cAutomatesPortfolio->mRetornaDados("");
        for ($i=0; $i < 8 ; $i++) { 
            $cTpl->mSet("tt_image",$imgs[$i]['tt_image']);
            $cTpl->mSet("nm_citie",$imgs[$i]['nm_citie']);
            $cTpl->mSet("nm_image",$caminhoFotos.$imgs[$i]['nm_image']);
            for ($j=0; $j < count($portfolio) ; $j++) { 
                if ($portfolio[$j]['cd_portfolio']==$imgs[$i]['cd_portfolio']) {
                    $cTpl->mSet("lk_portfolio",$imgs[$i]['cd_portfolio']."/".$cUrl->mTrataString($portfolio[$j]["sub_portfolio"]));
                }
            }
            $cTpl->mShow("inspiration_start");
        }
        $cTpl->mShow("inspiration_end");



        

        /******************************************

        CODIGO GLOBAL PARA ESTADOS CIDADES WEuBE

        ********************************************///

        // $cAutomatesStates = new cAutomates("states");
        // $states = $cAutomatesStates->mRetornaDados("");
        // for ($i=0; $i < 117 ; $i++) { 
        //     $cTpl->mSet("link","painting/" . $states[$i]['state_code'] . "/". $cUrl->mTrataString($states[$i]["state_name"]."-painting-contractors"));
        //     $cTpl->mSet("nm_citie",$states[$i]['state_code'] . " - " . $states[$i]['state_name']);
        //     $cTpl->mShow("while_cities");
        // }
        // $cTpl->mShow("fim_while_cities");

        /******************************************

        CODIGO CITIES MA - boston

        ********************************************///
        // $cAutomatesCities = new cAutomates("cities");
        // $cities = $cAutomatesCities->mRetornaDados("", "state_id = 22");

        // $cAutomatesStates = new cAutomates("states");
        // $state = $cAutomatesStates->mRetornaDados("", "id = 22");

        // for ($i=0; $i < 170 ; $i++) { 
        //     $cTpl->mSet("link","painting/" . $state[0]['state_code'] . "/". $cities[$i]["city"]."-painting-contractors/".$cities[$i]["id"]);
        //     $cTpl->mSet("nm_citie",$cities[$i]['city']);
        //     $cTpl->mShow("while_cities");
        // }
        // $cTpl->mShow("fim_while_cities");

        // $cAutomatesCities = new cAutomates("cities");
        // $cities = $cAutomatesCities->mRetornaDados("", "state_id = 22");
        // for ($i=170; $i < 340 ; $i++) { 
        //     $cTpl->mSet("link","painting/" . $state[0]['state_code'] . "/". $cities[$i]["city"]."-painting-specialists/".$cities[$i]["id"]);
        //     $cTpl->mSet("nm_citie",$cities[$i]['city']);
        //     $cTpl->mShow("while_cities2");
        // }
        // $cTpl->mShow("fim_while_cities2");

        // $cAutomatesCities = new cAutomates("cities");
        // $cities = $cAutomatesCities->mRetornaDados("", "state_id = 22");
        // for ($i=340; $i < 511 ; $i++) { 
        //     $cTpl->mSet("link","painting/" . $state[0]['state_code'] . "/". $cities[$i]["city"]."-painters-pro/".$cities[$i]["id"]);
        //     $cTpl->mSet("nm_citie",$cities[$i]['city']);
        //     $cTpl->mShow("while_cities3");
        // }
        // $cTpl->mShow("fim_while_cities3");


        $cAutomatesHome = new cAutomates("home_end");
        $home_end = $cAutomatesHome->mRetornaDados("");
        
        $cTpl->mSet("tt_tab2",$home_end[0]['tt_home_end']);
        $cTpl->mSet("tt_zip_code",$home_end[1]['tt_home_end']);

        $cTpl->mShow("conteudo_tabs");

        

        include "principalFim.php";
        break;
}
?>
