<?php
// Source: https://stackoverflow.com/questions/44548180/writing-values-to-ini-file

// Loads ini file data
function config_read($config_file) {
	$content = parse_ini_file($config_file, true);
	if($content != FALSE) return $content;
    return [];
}
// Update a setting in loaded inifile data
function config_set(&$config_data, $section, $key, $value) {
    $config_data[$section][$key] = $value;
}
// Serializes inifile config data back to disk.
function config_write($config_data, $config_file) {
    $new_content = "";
    foreach ($config_data as $section => $section_content) {
        $section_content = array_map(function($value, $key) {
            return "$key=$value";
        }, array_values($section_content), array_keys($section_content));
        $section_content = implode(PHP_EOL, $section_content);
        $new_content .= "[$section]".PHP_EOL."$section_content".PHP_EOL;
    }
    file_put_contents($config_file, $new_content);
}
// Short-hand function for updating a single config value in a file.
function config_set_file($config_file, $section, $key, $value) {
    $config_data = config_read($config_file);
	//print_r($config_data);
    config_set($config_data, $section, $key, $value);
    config_write($config_data, $config_file);
}
config_set_file("cfg.txt","user1","mail","asd@dasd.com");
config_set_file("cfg.txt","user1","name","Pepa");
config_set_file("cfg.txt","user1","hash","g5a46dfg6ad6fg6ad");

echo nl2br(file_get_contents("cfg.txt"));
?>
