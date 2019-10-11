<?php

define("CHUNK_SIZE", 1024*1024); // Size of file chunk in bytes

$debug = false;
$max_count = 0;
$max_filename = "";
$arr = array();

  function printDebugMessage($message) {
    global $debug;
    if ($debug) {
      print_r($message);
    }
  }

  function hashFilePartial($filename) { //option 1
    $handle = fopen($filename, 'r');
    $hash = hash_init('sha256');
    while (!feof($handle)) {
      $data = fread($handle, CHUNK_SIZE);
      hash_update($hash, $data);
    }
    fclose($handle);
    return hash_final($hash);
  }

  function hashFileFull($filename) { //option 2
    return hash_file('sha256', $filename);
  }

  function checkMax($filename) {
    global $arr, $max_count, $max_filename;

    $hash = hashFileFull($filename);
    // $hash = hashFilePartial($filename);

    printDebugMessage($hash."\n\n");

    if (array_key_exists($hash, $arr)){
      $arr[$hash] += 1;
      if ($max_count < $arr[$hash]) {
        $max_count = $arr[$hash];
        $max_filename = $filename;
      }
    } else {
      $arr[$hash] = 1;
      if (empty($max_filename)) {
        $max_count = 1;
        $max_filename = $filename;
      }
    }
  }


  function iterateDir ($dir) {
    if (is_file($dir)) {
      checkMax($dir);
    } elseif (is_dir($dir)) {
      $subdir = scandir($dir);
      foreach ($subdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
          iterateDir($dir . '/' . $value);
        }
      }
    }
  }

  function scanDuplicateContent($path) {
    global $max_filename, $max_count;
    iterateDir($path);
    return (file_get_contents($max_filename) . ' ' . $max_count . "\n\n");
  }

  function main() {
    global $argc, $argv, $max_filename, $max_count, $debug;
    if ($argc != 2 && $argc != 3) {
      print_r("php -f scan.php <path> [debug]");
      exit;
    }

    if ($argc == 3) {
      $debug = (strtolower($argv[2]) === 'debug');
    }

    $start_time = microtime(true);
    $content = scanDuplicateContent($argv[1]);
    $end_time = microtime(true);
    printDebugMessage("Execution time of script = ".($end_time - $start_time)." sec\n\n");
    printDebugMessage($max_filename . " " . filesize($max_filename) . " " . $max_count . "\n\n");

    print_r("Print the content? [y/n] ");
    $input = rtrim(fgets(STDIN));
    if (strtolower($input) === 'y') {
      print_r($content);
    }
  }

main();

?>
