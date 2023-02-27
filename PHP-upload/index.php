<html>
<head>
<title>Práce s obrázky</title>
</head>
<body>

<h1>Nahrávání obrázků</h1>
<hr>
<form action="index.php" name="form1" method="post" enctype="multipart/form-data">
Název: <input type="text" name="jmeno">
Soubor: <input name="obrazek" type="file">
<input type="submit" name="upload" value="Nahrát obrázek" onclick="if(document.form1.jmeno.value == ''){alert('Není vyplněné jméno obrázku!'); return false;}">
</form>

<?php 
$typ = "*/jpeg"||"*/jpg"||"*/png"||"*/gif"; // * , (.*?) , .*?
$misto = "./obrazky/"; // nakonci /

if(isset($_POST['upload'])){ 
if($_FILES['obrazek']['type'] == $typ){  
$soubor = move_uploaded_file($_FILES['obrazek']['tmp_name'], $misto . $_FILES['obrazek']['name']); 
  
if($soubor == "true"){  
echo "Obrázek <strong>" .$_FILES['obrazek']['name']. "</strong> byl úspěně nahrán a uloen jako <strong>" .$_POST['jmeno']. "</strong>.<br>"; 

$text = '<option value="./obrazky/' .$_FILES["obrazek"]["name"]. '" id="' .$_POST['jmeno']. '">' .$_POST['jmeno']. '</option>
';
file_put_contents('obrazky.txt', $text, FILE_APPEND);

}else{ 
echo "Někde se stala <strong>chyba</strong>, nic se nenehrálo!"; 
} 
}else{ 
echo 'Lze nahrávat pouze soubory typu <strong>jpeg, jpg, png a gif</strong>!'; 
} 
} 
?>
<br>

<h1>Mazání obrázků</h1>
<hr>
<select onchange="vypln(this)">
<option value="" id="">Vyber obrázek</option>
<?php
include("obrazky.txt");
?> 
</select> 
<script>function vypln(policko)
{
hodnota = policko.options[policko.selectedIndex].value
hodnota2 = policko.options[policko.selectedIndex].id

document.form2.id.value = hodnota2
document.form2.jmenom.value = hodnota
}
</script>

<form action="index.php" name="form2" method="post" enctype="multipart/form-data">
<input type="hidden" name="jmenom">
<input type="hidden" name="id">
<input type="submit" name="smazat" value="Smazat obrázek" onclick="if(document.form2.jmenom.value == ''){alert('Není vybrán obrázek!'); return false;}"></form>

<?php
if(isset($_POST['smazat'])){
$id = $_POST['id'];
unlink($_POST['jmenom']);

$string = file_get_contents('obrazky.txt');
$patterns = '/<option(.*?)>' .$id. '<\/option>/';
$replacements = '';
$string2 = preg_replace($patterns, $replacements, $string);
file_put_contents('obrazky.txt', $string2); 

echo "Obrázek <strong>" .$id. "</strong> byl úspěně smazán!";
}
?>
<br>

<h1>Nahrané obrázky</h1>
<hr>
<select onchange="document.getElementById('img').src = this.options[this.selectedIndex].value">
<option value="obrazky/zaklad.png">Vyber obrázek</option>
<?php
include("obrazky.txt");
?> 
</select>
<br> 
<img id="img" src="obrazky/zaklad.png" alt="Obrázek">
<br>
<a href="http://localhost/">Zpět</a>
</body>
</html>
