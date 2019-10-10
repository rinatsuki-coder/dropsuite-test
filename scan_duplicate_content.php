<?php

$max_count = 0;
$max_filename = "";
$arr = array();

  function checkMax($filename) {
    global $arr, $max_count, $max_filename;
    $hash = hash_file('sha256', $filename);
    // echo $hash . "\n";
    if (array_key_exists($hash, $arr)){
      $arr[$hash] += 1;
      // echo "max_count " . $max_count . " array[hash] " . $arr[$hash] . "\n";
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
    $subdir = scandir($dir);
    foreach ($subdir as $key => $value) {
      if (!in_array($value, array(".", ".."))) {
        $filename = $dir . '/' . $value;
        if (is_file($filename)) {
          //print_r($filename . "\n");
          checkMax($filename);
        } elseif (is_dir($filename)){
          iterateDir($filename);
        }
      }
    }
  }

  function main() {
    global $argc, $argv, $max_filename, $max_count;
    if ($argc != 2) {
      exit;
    }

    $start_time = microtime(true);
    iterateDir($argv[1]);
    print_r("\n" . file_get_contents($max_filename) . ' ' . $max_count . "\n\n");
    $end_time = microtime(true);
    print_r("Execution time of script = ".($end_time - $start_time)." sec\n\n");
  }

main();

?>
