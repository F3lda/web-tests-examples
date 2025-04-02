<?php
file_put_contents('fifo.txt', "test string\r\n", FILE_APPEND | LOCK_EX);
echo "OK";
?>
