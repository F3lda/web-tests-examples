<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');


// how long PHP script stays running/SSE connection stays open (seconds)
//set_time_limit(5);

$handle = fopen("fifo.txt", "r");

$start = hrtime(true);


$offset = 0;

while(1) {
	
	
	fseek($handle, $offset);
	
	while (($buffer = fgets($handle, 4096)) !== false) {
        echo $buffer. PHP_EOL;
		//echo str_repeat("\n", (int)(ob_get_status()['buffer_size'])-(int)(ob_get_status()['buffer_used'])); // disable output_buffering
    }
	$offset = ftell($handle);
	
	
	$end = hrtime(true);
	if ((($end - $start) / 1000000000) > 10) {
		break;
	}
	
	if ( connection_aborted() ) {
		
		
		//fclose($handle);
		break;
	}
	
	sleep(1);
}

fclose($handle);
?>
