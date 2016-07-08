<?php

class Db_Query {
  private $sql;
  public static function createFromFile($file) {
    return new query(file_get_contents('app/' . $file . '.sql'));
  }
  public function __construct($sql) {
    $this->sql = $sql;
  }
  public function bind($name, $value, $toBase64 = false) {
    if (!is_numeric($value)) {
      if (is_string($value)) {
        $value = addslashes($value);
      } elseif (is_object($value)) {
        $value = json_encode($value);
      } elseif (is_array($value)) {
        $value = str_replace(array('[', ']'), array('(', ')'), json_encode($value));
      }
      if ($toBase64) {
        $value = base64_encode($value);
      }
      $value = "'$value'";
    }
    $this->sql = str_replace(':' . $name, $value, $this->sql);
    return $this;
  }
  public function exec($sql) {
    $r = mysqli_query(Database::res(), $sql);
    $r2 = array();
    if (false === $r) {
      echo Database::res()->error;
      return false;
    } else if (true !== $r) {
      while ($row = mysqli_fetch_object($r)) {
        $r2[] = $row;
      }
      return $r2;
    }
    return true;
  }
  private function log() {
    if (isset($_GET['sql'])) {
      echo "\n";
      echo $this->sql . "\n";
      echo "====================================================================\n";
    }
  }
  public function insert() {
    $this->exec($this->sql);
    return Database::res()->insert_id;
  }
  public function select() {
    return $this->exec($this->sql);
  }
  public function get() {
    $r = $this->exec($this->sql);
    if (true !== $r and false !== $r and null != $r) {
      return $r[0];
    }
    return false;
  }
  public function update() {
    $this->exec($this->sql);
  }
  public function delete() {
    $this->exec($this->sql);
  }
}
