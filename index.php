<?php
require_once( "hybridauth/Hybrid/Auth.php" );
require_once( "hybridauth/Hybrid/Endpoint.php" ); 
session_start(); 
// change the following paths if necessary 
$config = 'hybridauth/config.php';
require_once( "hybridauth/Hybrid/Auth.php" );

// check for erros and whatnot
$error = "";
$provider = "";
if( isset( $_GET["error"] ) ){
   $error = '<b style="color:red">' . trim( strip_tags(  $_GET["error"] ) ) . '</b><br /><br />';
}
$user_data = NULL;
// if user select a provider to login with
// then inlcude hybridauth config and main class
// then try to authenticate te current user
// finally redirect him to his profile page
if( isset( $_GET["provider"] ) && $_GET["provider"] ):
    try{
            $provider = $_GET['provider'];
            // create an instance for Hybridauth with the configuration file path as parameter
            $hybridauth = new Hybrid_Auth( $config );
            // set selected provider name 
            $provider = @ trim( strip_tags( $provider ) );
            // try to authenticate the selected $provider
            $adapter = $hybridauth->authenticate( $provider );
            // if okey,  grab the user profile
            $user_data = $adapter->getUserProfile();

    }
    catch( Exception $e ){
            // In case we have errors 6 or 7, then we have to use Hybrid_Provider_Adapter::logout() to 
            // let hybridauth forget all about the user so we can try to authenticate again.
            // Display the recived error, 
            // to know more please refer to Exceptions handling section on the userguide
            switch( $e->getCode() ){ 
                    case 0 : $error = "Unspecified error."; break;
                    case 1 : $error = "Hybriauth configuration error."; break;
                    case 2 : $error = "Provider not properly configured."; break;
                    case 3 : $error = "Unknown or disabled provider."; break;
                    case 4 : $error = "Missing provider application credentials."; break;
                    case 5 : $error = "Authentication failed. The user has canceled the authentication or the provider refused the connection."; break;
                    case 6 : $error = "User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again."; 
                                    $adapter->logout(); 
                                    break;
                    case 7 : $error = "User not connected to the provider."; 
                                    $adapter->logout(); 
                                    break;
            } 
            // well, basically your should not display this to the end user, just give him a hint and move on..
            $error .= "<br /><br /><b>Original error message:</b> " . $e->getMessage(); 
            $error .= "<hr /><pre>Trace:<br />" . $e->getTraceAsString() . "</pre>";
    }
endif;
//Print the errors on the log, not the user
if(strlen($error)>0) error_log($error);
?>
<!DOCTYPE html>
<!--[if IEMobile 7 ]>    <html class="no-js iem7"> <![endif]-->
<!--[if (gt IEMobile 7)|!(IEMobile)]><!--> <html class="no-js" manifest="cache.manifest"> <!--<![endif]-->
    <!--  -->
    <head>
        <meta charset="utf-8">
        <title>WhatsApPolo</title>
        <!--AppCache management, if there's new manifest show a loading bar, else checknetworkstatus -->
        <script type="text/javascript">
            window.applicationCache.addEventListener('progress', function(e){
                var progress = document.getElementById('progress');
                var perc = Math.round((e.loaded/e.total)*100);
                progress.style.width = perc+'%';
                if(e.loaded==e.total) document.getElementById('loading').style.display = 'none';
            }, false);
            window.applicationCache.addEventListener('noupdate', function(){document.getElementById('loading').style.display='none';checkNetworkStatus()}, false);
            window.applicationCache.addEventListener('updateready', function(){document.getElementById('loading').style.display='none';checkNetworkStatus()}, false);
            window.applicationCache.addEventListener('error', function(){document.getElementById('loading').style.display='none';checkNetworkStatus()}, false);
        </script>
        
        <meta name="description" content="">
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="548">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0">
        <!-- for iPhone 5 -->
        <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1" media="(device-height: 568px)" />
        <meta http-equiv="cleartype" content="on">

        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/touch/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/touch/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/touch/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon-precomposed" href="img/touch/apple-touch-icon.png">
        <link rel="shortcut icon" href="img/touch/apple-touch-icon.png">

        <!-- Tile icon for Win8 (144x144 + tile color) -->
        <meta name="msapplication-TileImage" content="img/touch/apple-touch-icon-144x144.png">
        <meta name="msapplication-TileColor" content="#222222">


        <!-- For iOS web apps. Delete if not needed. https://github.com/h5bp/mobile-boilerplate/issues/94 -->
        <!---->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="WhatsApPolo">
        

        <!-- This script prevents links from opening in Mobile Safari. https://gist.github.com/1042026 -->
        <!---->
        <script>(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName)&&d.className!='image')d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")</script>
        
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/add2home.css">
        <link rel="stylesheet" href="css/photoswipe.css">
        <script type="application/javascript" src="js/vendor/add2home.js"></script>
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body id="whatsfake">
        <div id="fb-root"></div>
        <div id="loading">
            <div class="inner">
                <h1>Cargando interfaz</h1>
                <div class="progress progress-striped active">
                    <div id="progress" class="bar" style="width: 0%;"></div>
                </div>
            </div>
        </div>
        <div id="registre">
            <div id="landing">
                <img src="img/landing.png" alt="polo" style="width:100%"/>
            </div>
            <div id="buttons">
                <a href="?provider=Facebook" class="btn btn-large btn-primary fbbtn">
                    <i class="icon-facebook icon-large"></i>
                    Iniciar sesión con Facebook
                </a>
                <a href="?provider=Twitter" class="btn btn-large btn-info twbtn">
                    <i class="icon-twitter icon-large"></i>
                    Iniciar sesión con Twitter
                </a>
                <fieldset><legend>o si lo prefieres</legend></fieldset>
                <input id="name" type="text" class="input-large" placeholder="Dinos tu nombre" />
                <a href="#" id="anonim" class="btn btn-large">Entrar</a>
            </div>
        </div>
        <div id="head">
            <div id="title"><i class="wapolo"></i>WhatsApPolo
                <div class="btn-group pull-right" style="margin-right:20px;text-align:left">
                <a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-cog icon-large"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a id="clear" tabindex="-1" href="#"><i class="icon-trash"></i>Borrar conversación</a></li>
                    <li><a id="media" tabindex="-1" href="#"><i class="icon-camera"></i>Ver multimedia</a></li>
                </ul>
                </div> 
            </div>
        </div>
        <div id="container">
            <div id="conversation">
                <div id="bubbles">
                </div>
            </div>
            <div id="text" class="row-fluid">
                <input type="text" name="input" id="input" />
                <button id="send" class="btn btn-primary">Enviar</button>
            </div>
            <div id="mediadiv">
                <div id="mediacont">
                    
                </div>
            </div>
        </div>

        <script src="js/vendor/json2.js"></script>
        <script src="js/vendor/jquery-1.9.1.min.js"></script>
        <script src="js/vendor/underscore-min.js"></script>
        <script src="js/vendor/backbone-min.js"></script>
        <script src="js/vendor/backbone.localStorage.js"></script>
        <script type="text/javascript" src="js/vendor/klass.min.js"></script>
        <script type="text/javascript" src="js/vendor/code.photoswipe-3.0.5.min.js"></script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/vendor/iscroll.js"></script>
        <script src="js/helper.js"></script>
        <script src="js/main.js"></script>
        <script type="text/javascript">
            var user = <?=json_encode($user_data);?>;
            var provider = '<?=$provider?>';
            function checkNetworkStatus() {
                if (navigator.onLine) {
                    // Just because the browser says we're online doesn't mean we're online. The browser lies.
                    // Check to see if we are really online by making a call for a static JSON resource on
                    // the originating Web site. If we can get to it, we're online. If not, assume we're
                    // offline.
                    
                    $.ajax({async: true,
                        cache: false,
                        dataType: "json",
                        error: function (req, status, ex) {
                            console.log("Error: " + ex);
                            // We might not be technically "offline" if the error is not a timeout, but
                            // otherwise we're getting some sort of error when we shouldn't, so we're
                            // going to treat it as if we're offline.
                            // Note: This might not be totally correct if the error is because the
                            // manifest is ill-formed.
                            offline();
                        },
                        success: function (data, status, req) {
                            online();
                        },
                        timeout: 1000,
                        type: "GET",
                        url: "js/online.js"
                    });
                } else {
                    offline();
                }
            }
            function offline(){
                console.log('Offline');
                window.App = new AppView({status:'offline'});
            }
            function online(){
                console.log('Online');
                window.fbAsyncInit = function() {
                    FB.init({
                    appId      : '157133954443234', // App ID
                    channelUrl : '/channel.html', // Channel File
                    status     : true, // check login status
                    cookie     : true, // enable cookies to allow the server to access the session
                    xfbml      : true  // parse XFBML
                    });
                    window.App = new AppView({status:'online'});
                    // Additional init code here

                };

                // Load the SDK Asynchronously
                (function(d){
                    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
                    if (d.getElementById(id)) {return;}
                    js = d.createElement('script'); js.id = id; js.async = true;
                    js.src = "//connect.facebook.net/es_ES/all.js";
                    ref.parentNode.insertBefore(js, ref);
                }(document));
            }
            //checkNetworkStatus();
        </script>
        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.
        <script>
            var _gaq=[["_setAccount","UA-XXXXX-X"],["_trackPageview"]];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
            g.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";
            s.parentNode.insertBefore(g,s)}(document,"script"));
        </script> -->
    </body>
</html>
