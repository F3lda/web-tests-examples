<?php
//https://stackoverflow.com/questions/1342583/manipulate-a-string-that-is-30-million-characters-long/1342760#1342760


class SplitCurlByLines {

    public function curlCallback($curl, $data) {

        $this->currentLine .= $data;
        $lines = explode("\n", $this->currentLine);
        // The last line could be unfinished. We should not
        // proccess it yet.
        $numLines = count($lines) - 1;
        $this->currentLine = $lines[$numLines]; // Save for the next callback.

        for ($i = 0; $i < $numLines; ++$i) {
            $this->processLine($lines[$i]); // Do whatever you want
            ++$this->totalLineCount; // Statistics.
            $this->totalLength += strlen($lines[$i]) + 1;
        }
		
		if($this->totalLength > 30){ 
			//exit("DONE!");
			return 0;
		}
		
        return strlen($data); // Ask curl for more data (!= value will stop).

    }

    public function processLine($str) {
        // Do what ever you want (split CSV, ...).
        echo '<'.$str . ">\n";
    }

    public $currentLine = '';
    public $totalLineCount = 0;
    public $totalLength = 0;

} // SplitCurlByLines

// Just for testing, I will echo the content of Stackoverflow
// main page. To avoid artifacts, I will inform the browser about
// plain text MIME type, so the source code should be vissible.
//Header('Content-type: text/plain');

$splitter = new SplitCurlByLines();

// Configuration of curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://kajel.net/eventsource/server.php");
curl_setopt($ch, CURLOPT_WRITEFUNCTION, array($splitter, 'curlCallback'));

curl_exec($ch);

// Process the last line.
$splitter->processLine($splitter->currentLine);

curl_close($ch);

echo ($splitter->totalLineCount . " lines; " .
 $splitter->totalLength . " bytes.");
?>
