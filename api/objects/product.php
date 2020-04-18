<?php
class Product{
  
    // database connection and table name
    private $conn;
    private $table_name = "items";
  
    // object properties
    public $iNum;
    public $iName;
    public $iCost;
    public $iImage;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
    
        // select all query
        $query = "SELECT
                    p.iNum, p.iName, p.iCost, p.iImage
                FROM
                    " . $this->table_name . " p
                ";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
}
?>