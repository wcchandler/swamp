<?php

  ## does the stuff -- see README for specifics

  ## 3 actions can be given
  ##   - updateTime  --  returns time in preferred format
  ##   - socket      --  performs a socket test on something
  ##   - ping        --  pings via ICMP

  ##   - ping and socket expect JSON output
  ##     this is because i'm an idiot and can't understand jQuery

  ## checking if action is set then pulling in its value
  if(isset($_GET["action"])){
    $action = htmlspecialchars($_GET["action"]);
    ## TBD: check if passed secret is valid
  }else{
    $action = "ERROR";
    echo "error: no action given";
    return 0;
  }

  ## action : updateTime
  ##   returns current date/time in whatever format you want
  ## e.g.  /backend/php.php?action=updateTime

  if($action == "updateTime"){
    ## e.g. "Aug 23 - 14:33:03"
    echo date('M d - H:i:s');
    ## e.g. "14:33:03"
    #echo date('H:i:s');
    ## e.g. "2:33:03 pm"
    #echo date('g:i:s a');
    ## for custom strings : http://php.net/manual/en/function.date.php
    return 0;
  } # end of updateTime

  ## socket test needs the following:
  ##   server  : 127.0.0.1
  ##   type    : tcp
  ##   port    : 80
  ##   timeout : 2
  ## e.g. /backend/php.php?action=socket&server=127.0.0.1&type=tcp&port=80&timeout=2
  ##   expected output in JSON format
  ##   { "action" : "socket", "server" : "127.0.0.1", "type" : "tcp", "port" : "80", "timeout" : "2", "res" : "0" }

  if($action == "socket"){
    ## grab the vars...  because, why not?? memory's cheap
    $server = htmlspecialchars($_GET["server"]);
    $type = htmlspecialchars($_GET["type"]);
    $port = htmlspecialchars($_GET["port"]);
    $category = htmlspecialchars($_GET["category"]);
    $timeout = htmlspecialchars($_GET["timeout"]);
    ## not going to bother with another sanity check.  hopefully it's all good
    ## using fsockopen() : http://php.net/manual/en/function.fsockopen.php
    if($type == "udp"){
      $fp = fsockopen("udp://".$server, $port, $errno, $errstr, $timeout);
    }else{
      if($type == ""){
        $type="tcp";
      }
      $fp = fsockopen($server, $port, $errno, $errstr, $timeout);
    }
    if (!$fp){
      #echo "$errstr ($errno)<br />\n";  # useful for debugging
      ## bad connection
      $res = 0;
    }else{
      #$out = "GET / HTTP/1.1\r\n";
      #$out .= "Host: www.example.com\r\n";
      #$out .= "Connection: Close\r\n\r\n";
      #fwrite($fp, $out);
      #while (!feof($fp)) {
      #  echo fgets($fp, 128);
      #}
      ## good connection
      fclose($fp);
      $res = 1;
    }
    //$a = array("server" => $server, "type" => $type, "port" => $port, "timeout" => $timeout, "res" => $res);
    echo "{\"action\":\"".$action."\",\"server\":\"".$server."\",\"type\":\"".$type."\",\"port\":\"".$port."\",\"timeout\":\"".$timeout."\",\"category\":\"".$category."\",\"res\":\"".$res."\"}";
    return 0;
  } # end of socket

  ## ping action takes in these vars:
  ##   server  : 127.0.0.1
  ##   timeout : 2
  ## e.g. /backend/php.php?action=socket&server=127.0.0.1&timeout=2
  ##   expected output in JSON format
  ##   category is just a nice variable to pass back and forth... because i'm lazy
  ##   { "action" : "ping", "server" : "127.0.0.1", "timeout" : "2", "category" : "main", "res" : "0" }

  if($action == "ping"){
    ## there's no easy way to do this so we'll just call system(ping)...
    ## be nice to use socket_create() : http://php.net/manual/en/function.socket-create.php
    $category = htmlspecialchars($_GET["category"]);
    $server = htmlspecialchars($_GET["server"]);
    $timeout = htmlspecialchars($_GET["timeout"]);
    if($timeout == ""){
      $timeout = 2;
    }
    #echo "server : $server";
    #echo "timeout : $timeout";
    ## ping arguments:
    ##   -q  -- quiet
    ##   -n  -- don't resolve hostname
    ##   -c  -- count (just 1)
    ##   -w  -- wait or timeout
    ## using ping in 'iputils' package on ubuntu
    ## pretty sure it follows BSD return values
    ## http://www.manpagez.com/man/8/ping/
    ##   0 - good
    ##   1 - not good
    ##   2 - some other kind of error
    $res = exec("/bin/ping -q -n -c 1 -w $timeout $server", $outcome, $status);
    #echo "outcome : $outcome";
    #echo "status  : $status";
    #echo "res     : $res";
    if($status == 0){
      ## ping was good
      $res = 1;
    }else{
      $res = 0;
    }
    echo "{\"action\":\"".$action."\",\"server\":\"".$server."\",\"timeout\":\"".$timeout."\",\"category\":\"".$category."\",\"res\":\"".$res."\"}";
    return 0;
  } # end of ping


?>
