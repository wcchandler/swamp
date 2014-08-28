<!DOCTYPE html>

<?php

  $conf = "configs/" . htmlspecialchars($_GET["conf"]) . ".xml";
  $k = htmlspecialchars($_GET["key"]);
  $xmlstring = file_get_contents($conf);
  $xml = simplexml_load_string($xmlstring);
  $json = json_encode($xml);
  $array = json_decode($json,TRUE);

  $order = $array['devices']['order'];
  asort($order);
  $items = array_keys($order);

?>

<html>
  <head>
    <title>murmur - just a placeholder name</title>
    <!-- Include the bootstrap stylesheets -->
    <!-- originally pulled from wikipedia on Jul 22 2014 -->
    <!-- https://en.wikipedia.org/wiki/Bootstrap_%28front-end_framework%29 -->
    <!-- hotlinking -->
    <!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="http://bootswatch.com/slate/bootstrap.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <style>

      .panel {
        margin-bottom: 0px;
      }

      .label {
        display: inline-block;
      }

      .label-success {
        background-image: linear-gradient(#58AC58, #42A442 60%, #339E33);
      }

      .label-danger {
        background-image: linear-gradient(#AC5858, #A44242 60%, #9E3333);
      }

      .label {
        font-size: 85%;
        margin: 0.25em 0;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
        border-radius: 0.15em;
      }

      .badge-success {
        background-color: #339E33;
      }

      .badge-danger {
        background-color: #9E3333;
      }

      .navbar-bottom {
        text-align: center;
        margin: 20px 0px 10px 0px;
      }

      .well-sm {
        font-size: 90%;
      }

      /*   this shrinks the navbar, not working though
      .navbar-brand {
        height: 0%;
      }

      .navbar-nav > li > a {
        padding-top:5px !important; 
        padding-bottom:5px !important;
      }
      */

      .navbar {
        min-height:32px !important; 
        margin-bottom: 1px;
      }

    </style>

  </head>
 
  <body>

    <!-- start of navbar -->

    <div class="navbar navbar-default">
      <div class="navbar-header">
        <!-- start of hotdog menu -->
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <!-- end of hotdog menu -->
        <?php 
          if ($array['settings']['showBrand'] == "true"){
            print "\n<a class=\"navbar-brand\" href=\"#\">".$array['name']."</a>\n";
          }else{
            print "\n<a class=\"navbar-brand\" href=\"#\">murmur</a>\n";
          }
        ?>
      </div> <!-- end of navbar-header -->
      <div class="navbar-collapse collapse navbar-responsive-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">timeout: 1s <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li class="active"><a href="#">1 second</a></li>
              <li><a href="#">2 seconds</a></li>
              <li><a href="#">5 seconds</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">frequency: 30s <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li class="active"><a href="#">30 seconds</a></li>
              <li><a href="#">60 seconds</a></li>
              <li><a href="#">5 minutes</a></li>
            </ul>
          </li>
          <p class="navbar-text">Last updated: 19:38:03 (server time)</p>
        </ul>
      </div>
    </div>

    <!-- end of navbar -->

    <div class="container-fluid">

      <?php
        foreach ($items as $i){
          print "\n<!-- start of $i section -->\n<div class=\"row\"><div class=\"col-lg-12\">".
                "<h4>$i</h4><div class=\"panel panel-default\"><div class=\"panel-body\">";
          foreach ($array['devices']['node'] as $n) {
            if ($n['category'] == $i){
              print "<span class=\"label label-danger\"><strong>".$n['hostname']."</strong><br /><small>".$n['ip']."</small></span> ";
            }
          }
          print "</div></div></div></div>\n<!-- end of $i -->\n";
        } ## end of foreach item
      ?>

      <!-- start of log well -->
      <div class="row">
        <div class="col-lg-12">
          <h5><strong>events</strong></h5>
            <div class="well well-sm">
              RandomSwitch_3500 (172.20.1.107) is up; web app started Aug 23, 2014 20:05:10 (server time);
            </div>
        </div> <!-- end of col -->
      </div> <!-- end of row -->
      <!-- end of log well -->


      <div class="footer navbar-bottom">
        source on <a href="https://github.com/wcchandler/murmur">github</a>
      </div>

    </div> <!-- end of container-fluid -->

    <!-- JavaScript placed at the end of the document so the pages load faster -->
    <!-- Include the jQuery library -->
    <script src="//code.jquery.com/jquery-1.7.2.min.js"></script>
 
    <!-- Incorporate the Bootstrap JavaScript plugins -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  </body>
</html>
