<?php

include_once "ScannerDuplicate.php";

$debug = false;
if ($argc != 3 && $argc != 4) {
  print_r("php -f scan.php <path> <database: true|false> [debug]");
  exit;
}

if ($argc == 4) {
  $debug = (strtolower($argv[3]) === 'debug');
}
$usingDB = (strtolower($argv[2]) === 'true');

//Start scan duplicate content
$start_time = microtime(true);
$scanner = new ScannerDuplicate($debug, $usingDB);
$scanner->scanDuplicateContent($argv[1]);
if ($usingDB){
  $scanner->clearCount();
}
$end_time = microtime(true);
if ($debug){
  print_r("Execution time of script = ".($end_time - $start_time)." sec\n\n");
  print_r($scanner->getMaxFilename() . " " . $scanner->getMaxFilesize() . " " . $scanner->getMaxCount() . "\n\n");
}
//End scan duplicate content

print_r("Print the content? [y/n] ");
$input = rtrim(fgets(STDIN));
if (strtolower($input) === 'y') {
  print_r($scanner->getMaxContentFilename() . " " . $scanner->getMaxCount() . "\n\n");
}

?>
