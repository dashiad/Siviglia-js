<?php
/*+
 * Este script extrae línea a línea las traducciones de un fichero y las imprime en pantalla
 *
 */

function help()
{
    echo "Usage: php extractTranslations.php </path/to/file>\n";
    exit();
}

if ($argc != 2) {
    help();
}

$filename = $argv[1];
if (! $handler = @fopen($filename, "r")) {
    help();
}

$ltr = array();
while(!feof($handler)){
    $matches = array();
    $line = fgets($handler);
    if (preg_match('/\[\@T\]\[\_ID\](.*)\[\#\]\[\_C\]/', $line, $matches)) {
        $ltr[] = $matches[1];
    }
}

for ($i=0;$i<count($ltr);$i++) {
    echo $ltr[$i] . "\n";
}
exit();

