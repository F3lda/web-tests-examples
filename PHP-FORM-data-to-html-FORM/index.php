<?php
echo "<pre>";
print_r($_POST);
print_r($_GET);
echo "</pre>";
echo "<form  target=\"_blank\" action=\"./\" onsubmit=\"this.action = document.getElementById('URL').value; return true;\" method=\"POST\"><label for=\"URL\">FORM URL: </label> <input type=\"text\" id=\"URL\"><hr>";
foreach ($_POST as $param_name => $param_val) {
    echo "<label for=\"$param_name\">$param_name\:</label><input type=\"text\" id=\"$param_name\" name=\"$param_name\" value=\"$param_val\"><br>\n";
}
echo "<hr><input type=\"submit\" value=\"Submit\">
</form>";
?>