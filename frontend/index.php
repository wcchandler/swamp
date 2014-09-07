<!DOCTYPE html>

<?php

  ## load the conf file if we got it
  ## otherwise set conf to NULL

  if(isset($_GET["conf"])){
    $conf = "configs/" . htmlspecialchars($_GET["conf"]) . ".xml";
    if(isset($_GET["key"])){
      $k = htmlspecialchars($_GET["key"]);
    }
    $xmlstring = file_get_contents($conf);
    $xml = simplexml_load_string($xmlstring);
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);
    ## check the key
    if($k != $array['key']){
      $conf = "BAD_KEY";
    }
    ## sort the nodes
    $order = $array['devices']['order'];
    asort($order);
    $items = array_keys($order);
    $timeout = $array['settings']['timeout'];
    $frequency = $array['settings']['frequency'];
    $hotlink = $array['settings']['hotlink'];
  }else{
    $conf = "NULL";
  }

?>

<html>
  <head>
    <title>murmur</title>
    <!-- Include the bootstrap stylesheets -->
    <!-- originally pulled from wikipedia on Jul 22 2014 -->
    <!-- https://en.wikipedia.org/wiki/Bootstrap_%28front-end_framework%29 -->
    <!-- hotlinking -->
    <!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet"> -->
    <?php 
      if($hotlink){
        print "<link href=\"http://bootswatch.com/slate/bootstrap.css\" rel=\"stylesheet\">\n";
      }else{
        print "<link href=\"frontend/bootstrap.css\" rel=\"stylesheet\">\n";
      }
    ?>
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

      .label-default {
        background-image: linear-gradient(#585858, #424242 60%, #333333);
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

      .text-success {
        color: #339E33;
      }
      .text-danger {
        color: #9E3333;
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
    <?php
      ## could probably implement some checks...  but it shouldn't matter.
      ## let's set some js vars
      print "\n<script>".
            "\nvar timeout=".$timeout.";".
            "\nvar frequency=".$frequency.";".
            "\n</script>\n";
    ?>
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
          <!--   HOLY CRAP THIS IS A LOT OF WORK...  'F it
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">timeout: <script>document.write(timeout);</script>s <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li class="active"><a href="#">1 second</a></li>
              <li><a href="#">2 seconds</a></li>
              <li><a href="#">5 seconds</a></li>
              <li class="divider"></li>
              <li><a href="#"><?php #echo $timeout;?> second</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">frequency: <script>document.write(frequency);</script>s <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li class="active"><a href="#">30 seconds</a></li>
              <li><a href="#">60 seconds</a></li>
              <li><a href="#">5 minutes</a></li>
              <li class="divider"></li>
              <li><a href="#"><?php #echo $frequency;?> seconds</a></li>
            </ul>
          </li>
          -->
          <li><a href="#">timeout: <?php echo $timeout; ?>s</a></li>   
          <li><a href="#">frequency: <?php echo $frequency; ?>s</a></li>   
          <li><p class="navbar-text">Last updated: <span id="timeUpdated">-</span> (server time)</p></li>
        </ul>
      </div>
    </div>

    <!-- end of navbar -->

    <div class="container-fluid">

      <?php
        if(($conf != "NULL") && ($conf != "BAD_KEY")){
          ## we are good to go, conf was passed with a valid key
          foreach ($items as $i){
            print "\n<!-- start of $i section -->\n<div class=\"row\"><div class=\"col-lg-12\">".
                  "<h4>$i <span id=\"".$i."-success\" class=\"badge badge-success\">0</span> <span id=\"".$i."-danger\" class=\"badge badge-danger\">0</span> </h4>".
                  "<div class=\"panel panel-default\"><div class=\"panel-body\">";
            foreach ($array['devices']['node'] as $n) {
              if ($n['category'] == $i){
                if($n['check'] == "ping"){
                  print "<span class=\"label label-default\" id=\"ping-".$n['ip'].
                        "\"><strong>".$n['hostname']."</strong><br /><small>".$n['ip']."</small></span> ";
                  ## generates our javascript string
                  $js_str = $js_str."$.getJSON(\"backend/php.php?action=ping&category=".$i."&server=".$n['ip']."&timeout=".$timeout."\",callBack);\n";
                  ##//  THIS IS GOOD!!!   $.getJSON("backend/php.php?action=ping&server=198.199.99.234&timeout=2",callBack);
                }else{
                  print "<span class=\"label label-default\" id=\"socket-".$n['ip']."-".$n['port'].
                        "\"><strong>".$n['hostname']."</strong><br /><small>".$n['ip']." (".$n['port'].")</small></span> ";
                  ## generates our javascript string
                  $js_str = $js_str."$.getJSON(\"backend/php.php?action=socket&category=".$i."&server=".$n['ip']."&timeout=".$timeout."&port=".$n['port']."\",callBack);\n";
                }
              }
            }
            print "</div></div></div></div>\n<!-- end of $i -->\n";
          } ## end of foreach item
        } ## end of if/else conf statement
      ?>

      <!-- start of log well -->
      <div class="row">
        <div class="col-lg-12">
          <h5><strong>events</strong></h5>
            <div class="well well-sm" id="events">
              <?php
                if($conf == "NULL"){
                  print "<h4>error:</h4><p>No conf loaded.  Looking for a <a href=\"?conf=demo\">demo</a>?</p>";
                }elseif($conf == "BAD_KEY"){
                  print "<h4>error:</h4><p>Bad or invalid key.</p>";
                }else{
                  print "";
                }
              ?>
            </div>
        </div> <!-- end of col -->
      </div> <!-- end of row -->
      <!-- end of log well -->


      <div class="footer navbar-bottom">
        source on <a href="https://github.com/wcchandler/murmur">github</a><br />
        v<?php echo file_get_contents('VERSION'); ?><br />
      </div>

    </div> <!-- end of container-fluid -->

    <!-- JavaScript placed at the end of the document so the pages load faster -->
    <!-- Include the jQuery library -->
    <!-- Incorporate the Bootstrap JavaScript plugins -->
    <?php 
      if($hotlink){
        print "<script src=\"//code.jquery.com/jquery-1.7.2.min.js\"></script>\n".
              "<script src=\"//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js\"></script>\n";
      }else{
        print "<script src=\"frontend/jquery-1.7.2.min.js\"></script>\n".
              "<script src=\"frontend/bootstrap.min.js\"></script>\n";
      }
    ?>



    <!-- here's the magic of this thing -->
    <script>

      var freq = <?php echo $frequency*1000; ?>;
      function pinger(){
        // this thing gets plowed through every XX seconds
        // it needs to contain a poll for every div above

        // first off, update the time in top right
        $("#timeUpdated").load("backend/php.php?action=updateTime");
        
        // now do the checks and update the divs as necessary
        // example str output ::
        //   $.getJSON("ping.php?ping=74.125.228.103",callBack);
        <?php echo $js_str; ?>
        //   this might work...
        //$().load("backend/php.php?action=ping&server=66.198.111.126",{div:"ping-66.198.111.126"},callBack);
        //$("#ping-66.198.111.126").load("backend/php.php?action=ping&server=66.198.111.126", callBack);
        //$.get("backend/php.php?action=ping&server=66.198.111.126",callBack);
        //$("#ping-66.198.111.126").toggleClass("label-danger"); 
        //$("#ping-66.198.111.126").toggleClass("label-success"); 
        //  THIS IS GOOD!!!   $.getJSON("backend/php.php?action=ping&server=198.199.99.234&timeout=2",callBack);
        setTimeout(pinger, freq);
      }


      function callBack(json){
        if(json.action == "ping"){
          var id = "#ping-"+json.server;
        }else{
          var id = "#socket-"+json.server+"-"+json.port;
        }
        id = id.replace(/\./g,'\\.');
        var catUp = $("#"+json.category+"-success").text();
        var catDown = $("#"+json.category+"-danger").text();
        //document.write("#"+json.action+"-"+json.server+"; res = "+json.res+";");
        if(json.res == "1"){
          //alert(($(id).hasClass("label-danger")));
          //alert(($("#"+json.action+"-"+json.server).hasClass("label-success")));
          //if($("#"+json.action+"-"+json.server).hasClass("label-success")){ 
          if($(id).hasClass("label-success")){ 
            //alert(" res == 1; do nothing ");
            // it was up and is now up, do nothing
          //}else if($("#"+json.action+"-"+json.server).hasClass("label-danger")){ 
          }else if($(id).hasClass("label-danger")){
            ////if(catDown != "0"){
            ////  catDown--;
            ////}
            catDown--;
            catUp++;
            $("#"+json.category+"-success").text(catUp);
            $("#"+json.category+"-danger").text(catDown);
            //alert(" id == \""+id+"\"; toggle both ");
            //alert(" res == 1; toggle both ");
            // it was down and is now up, get rid of down, add up
            // by default we're setting to down
            $(id).toggleClass("label-danger"); 
            $(id).toggleClass("label-success"); 
            $("#events").prepend($(id).text()+" came <span class=\"text-success\">up</span> at <strong>"+$('#timeUpdated').text()+"</strong>; ");
            ////$("#"+json.action+"-"+json.server).toggleClass("label-danger"); 
            ////$("#"+json.action+"-"+json.server).toggleClass("label-success"); 
            ////$("#events").prepend($('#'+json.action+'-'+json.server).text()+" came <span class=\"success\">up</span> at <strong>"+$('#updateTime').text()+"</strong>; ");
          }else{
            //alert(" res == 1; toggle label-success only ");
            // it was nothing, now up, add up
            catUp++;
            $("#"+json.category+"-success").text(catUp);
            $(id).toggleClass("label-default"); 
            $(id).toggleClass("label-success"); 
            //$("#"+json.action+"-"+json.server).toggleClass("label-success"); 
            //$("#events").prepend($('#'+json.action+'-'+json.server).text()+" is <span class=\"success\">up</span>; ");
          }
        }else if(json.res == "0"){
          if($(id).hasClass("label-danger")){ 
            // it was down and is now down, do nothing
          //}else if($(id).hasClass("label-success")){ 
          }else if($(id).hasClass("label-success")){
            // it was down and is now up, get rid of up, add down
            // add an alert or something here, news feed???
            ////if(catUp != "0"){
            ////  catUp--;
            ////}
            catUp--;
            catDown++;
            $("#"+json.category+"-success").text(catUp);
            $("#"+json.category+"-danger").text(catDown);

            $(id).toggleClass("label-success");
            $(id).toggleClass("label-danger"); 
            $("#events").prepend($(id).text()+" went <span class=\"text-danger\">down</span> at <strong>"+$('#timeUpdated').text()+"</strong>; ");
          }else{
            // it was default, now down, add down
            catDown++;
            $("#"+json.category+"-danger").text(catDown);
            $(id).toggleClass("label-default"); 
            $(id).toggleClass("label-danger"); 
            //$("#"+json.id).toggleClass("down");
            //$("#feed").prepend($('#'+json.id).text()+" is <span class=\"feeddown\">down</span>; ");
          }
        }
      } // end of callBack

      // now initialize our script

      //$("#events").append($(' web app started ').load('backend/php.php?action=updateTime'));
      //$("#events").load("backend/php.php?action=updateTime");
      var conf = "<?php echo $conf; ?>";
      if(conf != "NULL" && conf != "BAD_KEY"){
        $("#events").load("backend/php.php?action=updateTime", 
          function(){
            $("#events").prepend("web app started ");
          });
      }

      //$(" web app started;").appendTo('#events');
      pinger();
    </script>

  </body>
</html>
