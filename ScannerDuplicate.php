<?php
class ScannerDuplicate{

  private $_debug = false;
  private $_arr = array();
  private $_maxCount = 0;
  private $_maxFilename = "";
  private $_nCore = 4;

  public function __construct($_debug) {
    $this->_debug = $_debug;
  }

  public function getMaxCount() {
    return $this->_maxCount;
  }

  public function getMaxFilename() {
    return $this->_maxFilename;
  }

  public function getMaxContentFilename() {
    return file_get_contents($this->_maxFilename);
  }

  public function getMaxFilesize(){
    return filesize($this->_maxFilename);
  }

  public function printDebugMessage($message) {
    if ($this ->_debug) {
      print_r($message);
    }
  }

  private function _hashFileFull($filename) { //option 1
    return hash_file('sha256', $filename);
  }

  private function _hashFilePartial($filename) { //option 2
    $handle = fopen($filename, 'r');
    $hash = hash_init('sha256');
    while (!feof($handle)) {
      $data = fread($handle, CHUNK_SIZE);
      hash_update($hash, $data);
    }
    fclose($handle);
    return hash_final($hash);
  }

  private function _hashFileExecNative($filename) { //option 3
    return substr(exec('sha256sum ' . $filename), 0, 64);
  }

  private function _checkMax($filename) {
    // $hash = $this->_hashFileFull($filename);
    // $hash = $this->_hashFilePartial($filename);
    $hash = $this->_hashFileExecNative($filename);

    $this->printDebugMessage($filename."\n");
    $this->printDebugMessage($hash."\n\n");

    if (array_key_exists($hash, $this->_arr)){
      $this->_arr[$hash] += 1;
      if ($this->_maxCount < $this->_arr[$hash]) {
        $this->_maxCount = $this->_arr[$hash];
        $this->_maxFilename = $filename;
      }
    } else {
      $this->_arr[$hash] = 1;
      if (empty($this->_maxFilename)) {
        $this->_maxCount = 1;
        $this->_maxFilename = $filename;
      }
    }
  }

  public function scanDuplicateContent ($dir) {
    if (is_file($dir)) {
      $this->_checkMax($dir);
    } elseif (is_dir($dir)) {
      $subdir = scandir($dir);
      foreach ($subdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
          $this->scanDuplicateContent($dir . '/' . $value);
        }
      }
    }
  }
}

?>
