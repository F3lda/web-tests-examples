<?php
//https://github.com/hoaproject/Eventsource
//https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events
//https://developer.mozilla.org/en-US/docs/Web/API/EventSource


//ob_end_clean();

// how long PHP script stays running/SSE connection stays open (seconds)
//set_time_limit(60);


//date_default_timezone_set("America/New_York");
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

//ob_end_clean(); // disable output buffer
//ob_implicit_flush(); // call flush() automatically after every output
//ob_end_flush();


$counter = rand(1, 10);
while (true) {

  // Every second, send a "ping" event.

  echo "event: ping\n";
  $curDate = date(DATE_ISO8601);
  echo 'data: {"time": "' . $curDate . ' -> '.$_SERVER['REMOTE_ADDR'] . ":" . $_SERVER['REMOTE_PORT'].'"}';
  //echo "\n\n";
  echo PHP_EOL . PHP_EOL;

  // Send a simple message at random intervals.

  $counter--;

  if (!$counter) {
    echo 'data: This is a message at time ' . $curDate;
    $counter = rand(1, 10);
  }
  
  echo PHP_EOL . PHP_EOL;


	//ob_flush();
  //flush();
 /* 
 while(ob_get_level()) {
   ob_end_flush(); 
} 
flush();*/
echo str_repeat("\n",4096); // disable output_buffering

  // Break the loop if the client aborted the connection (closed the page)

  if ( connection_aborted() ) break;

  //sleep(1);
	usleep(500000);
}
?>