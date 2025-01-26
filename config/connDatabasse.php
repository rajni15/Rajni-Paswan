<?php 
class Database{

    private $db_servername ="localhost";
    private $db_username ="root";
    private $db_password ="";
    private $db_name ="testing";
    private $msqli="";
    private $results=array();
    private $mysqli="";
    private $conn=false;

    public function __construct(){

       if(!$this->conn){
          this->mysqli=new mysqli($this->db_servername,$this->db_username,$this->db_password,$this->db_name);
          $this->conn=true;
             if($this->mysqli->connect_errnor){
                array_push($this->result,$this->mysqli->connect_errnor);
             }
        }
    }
    public function insert($table ,$params=array()){
           if($this->tableExists($table)){
                 print_r($params);
                $table_colunms=implode(',',array_keys($params));
                $table_value=implode("','",$params);
                $sql="INSERT INTO $table ('table_columns') VALUES(''$table_value)";
           }
    }
    public function update(){
        
    }
    public function delete(){
        
    }
    public function select(){
        
    }
private function tableExists($table){
   $sql= "SHOW TABLES FROM $this->db_name LIKE '$table'";
    $taableInDb = $this->mysqli->query($sql);
    if($taableInDb){
        if($taableInDb->num_row==1){
return true;

        }else{
            array_push($this->result,$table."does not exist in this database ");
            return false;
        }
    }
}
    public function __destruct(){
        if($this->conn){
            if($this->mysqli->close()){
                $this->conn=false;
            }
        }else{
            return false;
        }
    }

}
?>