<?php

class MySqlite {

  private $_db;

  public function __construct() {
    $this->_db = new SQLite3("tmp.db");
    $this->_db->query('CREATE TABLE IF NOT EXISTS collection (
        id varchar PRIMARY KEY NOT NULL,
        count int DEFAULT 1
    );');
  }

  public function insert($hash, $count) {
    $statement = $this->_db->prepare('INSERT INTO collection (id, count)
        VALUES (:id, :count);');
    $statement->bindValue(':id', $hash);
    $statement->bindValue(':count', $count);
    $statement->execute();
  }

  public function update($hash, $count){
    $statement = $this->_db->prepare('UPDATE collection
        SET count=? where id=?;');
    $statement->bindValue(1, $count);
    $statement->bindValue(2, $hash);
    $statement->execute();
	}

  public function getAll(){
    $count = $this->_db->query('SELECT count FROM collection;');
    return $count->fetchArray(SQLITE3_ASSOC);
  }

  public function getById($hash){
		$statement = $this->_db->prepare('SELECT count FROM "collection" WHERE "id" = ?;');
    $statement->bindValue(1, $hash);
    $count = $statement->execute();

    return $count->fetchArray(SQLITE3_ASSOC);
	}

  public function dropTable(){
    return $this->_db->exec('DROP TABLE collection;');
  }

  public function close(){
    $this->_db->close();
    unlink("tmp.db");
  }
}


?>
