<?php
class Auto
{
    private $color;
    private $modelo;
    private $marca;
    private $id;
    public function __construct($color,$modelo,$marca) {
        $this->color= $color;
        $this->modelo=$modelo;
        $this->marca=$marca;
    }
    public function get_color(){
        return $this->color;
    }
    public function get_modelo(){
        return $this->modelo;
    }
    public function get_marca(){
        return $this->marca;
    }
    public function get_id(){
        return $this->id;
    }
    public function set_color($color){
        $this->color=$color;
    }
    public function set_modelo($modelo){
        $this->modelo=$modelo;
    }
    public function set_marca($marca){
        $this->marca=$marca;
    }
    public function set_id($id){
        $this->id=$id;
    }
}

class PdoEx
{
    private $servername;
    private $username;
    private $password;
    private $DBMS;
    private $dbName;
    private $conn;
    public function __construct($servername,$username,$password,$DBMS,$dbName) {
        $this->servername = $servername;
        $this->username=$username;
        $this->password=$password;
        $this->DBMS=$DBMS;
        $this->dbName=$dbName;
    }
    public function connect()
    {
        try {
            $this->conn=new PDO($this->DBMS.":host=".$this->servername.";dbname=".$this->dbName,$this->username,$this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Conexión correcta";
        } catch (PDOException $e) {
            echo "Conexión fallida".$e->getMessage();
        }
    }
    public function insert($auto)
    {
        $query="insert into autos(color,modelo,marca) values('".$auto->get_color()."','".$auto->get_modelo()."','".$auto->get_marca()."')";
        $this->conn->exec($query);
        echo "<br>Registro guardado correctamente";
    }
    public function delete($id){
        $query="delete from autos where id=".$id;
        if ($this->conn->exec($query)===TRUE){
            echo "Registro con el id = ".$id." eliminado correctamente";
        } else {
            echo "Error al eliminar el registro con el id = ".$id;
        }
    }
    public function update($auto){
        $query="update autos set color='".$auto->get_color()."',modelo='".$auto->get_modelo()."', marca='".$auto->get_marca()."' where id=".$auto->get_id(); 
        $this->conn->exec($query);      
    }
    public function select_all(){
        $query="Select * from autos";
        $result=$this->conn->query($query);
        echo '<table align="center"> <tr> <th>ID</th> <th>Color</th> <th>Modelo</th> <th>Marca</th> </tr>';
        while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
           echo "<tr>
                    <td>".$row['id']."</td>
                    <td>".$row['color']."</td>
                    <td>".$row['modelo']."</td>
                    <td>".$row['marca']."</td>
                </tr>";   
        }
        echo "</table>";
    }
    public function get_last_id(){ //Este solo puede llamarse después de un insert
        return $this->conn->lastInsertId();
    }
    public function createTable(){
        $this->conn->exec("drop table if exists autos");
        $table="create table autos(id integer auto_increment primary key, color varchar(50), modelo varchar(100), marca varchar(100))";
        $this->conn->exec($table);
        echo "<br>Tabla autos creada exitosamente";
    }
    public function close_connection()
    {
        $this->conn=null;
    }
}
$sentra=new Auto("Rojo","Sentra","Nissan"); //Crea un objeto auto con la clase modelo de la parte superior
$pdo_con=new PdoEx("localhost","root","","mysql","automoviles"); //Envía los parámetros de la conexión a la base de datos
$pdo_con->connect(); //Realiza la conexión con la base de datos
//$pdo_con->createTable(); //Crea la tabla si no existe, si existe solo comenta esta linea
$pdo_con->insert($sentra); //Inserta el aúto creado en la base de datos
$pdo_con->select_all();//Muestra registros en una tabla
?>