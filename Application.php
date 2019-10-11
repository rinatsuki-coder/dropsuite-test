<?php

include_once "ScannerDuplicate.php";

$debug = false;
if ($argc != 2 && $argc != 3) {
  print_r("php -f scan.php <path> [debug]");
  exit;
}

if ($argc == 3) {
  $debug = (strtolower($argv[2]) === 'debug');
}

$start_time = microtime(true);
$scanner = new ScannerDuplicate($debug);
$scanner->scanDuplicateContent($argv[1]);
$end_time = microtime(true);
if ($debug){
  print_r("Execution time of script = ".($end_time - $start_time)." sec\n\n");
  print_r($scanner->getMaxFilename() . " " . $scanner->getMaxFilesize() . " " . $scanner->getMaxCount() . "\n\n");
}

print_r("Print the content? [y/n] ");
$input = rtrim(fgets(STDIN));
if (strtolower($input) === 'y') {
  print_r($scanner->getMaxContentFilename() . " " . $scanner->getMaxCount() . "\n\n");
}

?>
