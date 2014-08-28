<html>
  <body>
    <?php
      
      ###  this file is to stupidly check the xml file to see if it's in a format we like
      ###    it's also to add verbosity to how the vars get sucked in 
 
      ini_set('display_errors',1);
      ini_set('display_startup_errors',1);
      error_reporting(-1);

      $conf = "configs/" . htmlspecialchars($_GET["conf"]) . ".xml";
      $k = htmlspecialchars($_GET["key"]);
      print "Pulling in this file: $conf with this key: $k";
      $xmlstring = file_get_contents($conf);
      print "\nThis is its string: <br />\n\"\"\"<br />$xmlstring\n<br />\"\"\"\n<br />";
      $xml = simplexml_load_string($xmlstring);
      print "<br />loaded in to string<br />";
      $json = json_encode($xml);
      print "<br />json encoded<br />";
      $array = json_decode($json,TRUE);
      print "<br />now as an array<br />";
      print_r($array);

      print "<br />\nNow to parse some vars<br />\n";
      print "<br />... but first!!! does the key match??? \n";
      $key = $array['api']['key'][0];
      print "<br />\$key = \"$key\";";
      if ($key == $k){
        print " MATCH! ";
      }else{
        print " <br /><b>INVALID KEY, DONE PARSING</b><br /><br /> ";
        exit;
      }
      $name = $array['name'];
      print "<br />\$name = \"$name\";";
      $showIP = $array['settings']['showIP'];
      print "<br />\$showIP = \"$showIP\";";
      $showHostName = $array['settings']['showHostName'];
      print "<br />\$showHostName = \"$showHostName\";";
      $showPort = $array['settings']['showPort'];
      print "<br />\$showPort = \"$showPort\";";
      $showBrand = $array['settings']['showBrand'];
      print "<br />\$showBrand = \"$showBrand\";";
      $timeout = $array['settings']['timeout'];
      print "<br />\$timeout = \"$timeout\";";
      $frequency = $array['settings']['frequency'];
      print "<br />\$frequency = \"$frequency\";";

      print "<br />How are we sorting these?";
      $order = $array['devices']['order'];
      asort($order);
      $items = array_keys($order);
      ##$order = array_keys($order);
      print "<br />Here's the categories in order: ";
      print_r($order);
      print "<br />Here's the category keys in order: ";
      print_r($items);
      print "<br />Now all the settings are pulled in, let's grab the nodes";

      foreach ($array['devices']['node'] as $n) {
        ### $n is an array containing "category" "check" "hostname" "ip"
        print "<br /><br />\$n['category'] = \"" . $n['category'] . "\";";
        print "<br />\$n['check'] = \"" . $n['check'] . "\";";
        print "<br />\$n['hostname'] = \"" . $n['hostname'] . "\";";
        print "<br />\$n['ip'] = \"" . $n['ip'] . "\";";
      }

      print "<br />Now let's put the nodes in their respective categories";
      foreach ($items as $i){
        print "\n<br />category :: \"$i\"";
        foreach ($array['devices']['node'] as $n) {
          if ($n['category'] == $i){
            print "\n<br />&nbsp;&nbsp;&nbsp;&nbsp;\$n['ip'] = \"" . $n['ip'] . "\";";
          }else{
            ## print "<br />MISS\$n['ip'] && \$n['category'] = \"" . $n['ip'] . "\" && \"" . $n['category'] . "\";";
          }
        }
      } ## end of foreach item

     print "\n\n<br /><br />Finished processing... hope it all looks good!<br /><br />\n";

    ?>
    


    <br />
  </body>
</html>

