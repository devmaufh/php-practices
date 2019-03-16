<?php
//Declaración de la clase usuarios
class Usuarios
{
    private $mail;
    private $password;
    public function __construct($mail, $password) {
        $this->mail=$mail;
        $this->password=$password;
    }
    public function set_mail($mail){
        $this->mail=$mail;
    }
    public function set_password($pass)
    {
        $this->password=$pass;
    }   
    public function get_mail()
    {
        return $this->mail;
    }
    public function get_password()
    {
        return $this->password;
    }
}
//Declaración de la clase Db
class Db
{
    # Atributos de la clase
    protected $servername;
    protected $username;
    protected $password;
    protected $dbName;
    protected $conn;
    public $status;
    //Constructor de la clase
    public function __construct($servername,$username,$password,$dbName) {
        $this->servername=$servername;
        $this->username=$username;
        $this->password=$password;
        $this->dbName=$dbName;
    }
    //Conecta la instancia declarada con la base de datos
    public function connect(){
        $this->conn=new mysqli($this->servername,$this->username,$this->password,$this->dbName);
        if($this->conn->connect_error){
           // die("Conexión fallida".$this->conn->connect_error);
            $this->status=FALSE;
        }else{
            $this->status=TRUE;
        }
    }
    
    //Inserta un usuario que recibe de parámetro en la base de datos
    public function insert($user)
    {
        $query="insert into users(mail,password) values('".$user->get_mail()."','".$user->get_password()."')";
        $this->exec_query($query,"Registro insertado correctamente","Error al insertar registro");    
    }

    //Elimina usuario en base al 'id' de la base de datos
    public function delete_by_id($id)
    {
        $query="delete from users where id=$id";
        $this->exec_query($query,"Registro eliminado correctamente","Error al eliminar el registro");    
    }
    
    //Actualiza el usuario de la base de datos
    public function update_by_id($user,$condition)
    {
        $query="update users set mail='".$user->get_mail()."',password='".$user->get_password()."' ".$condition;
        $this->exec_query($query,"Registro actualizado correctamente","Error al actualizar");   
    }

    //Selecciona todo de la tabla usuarios
    public function select_all(){
        $query="Select * from users";
        $result=$this->exec_select($query);
        while ($row=$result->fetch_assoc()) {
            echo $row['mail']." ".$row['password']."<hr>";
        }
    }

    public function select_2($query){
        $result=$this->exec_select($query);
        while($row=$result->fetch_assoc()){
            echo $row['mail']."<hr>";
        }
    }

    //Función estática para construir consultas con múltiples campos
    /* 
    No es necesario declarar un objeto de ésta clase para usarla ya que es una función estática.
    Los parámetros que necesita son los siguientes: 
    --> Nombre de la tabla que se consultará
    --> La condición del select (Puedes dejar comillas vacías en este parámetro ya que no es obligatorio)
    --> Campos que vas a seleccionar separados por compas
    Ejemplo de uso:
            Db::multiple_select("Users","where id=10","mail","password") // Con condición   
            Db::multiple_select("Users","","mail") // Sin condición y solo se selecciona el campo mail
            
    Esta función solo retorna un String con la consulta generada.
    */
    public static function multiple_select()
    {
        $query="Select ";
        for ($i=2; $i<func_num_args(); $i++) {
            if ($i==func_num_args()-1)
                $query=$query.func_get_arg($i);
            else
                $query=$query.func_get_arg($i).","; 
        }
        $query=$query." from ".func_get_arg(0)." ".func_get_arg(1);
        return $query;
    }

    // Función que ejecuta las consultas select y devuelve el resultado de la consulta
    private function exec_select($query){
        if($this->status){
            $result=$this->conn->query($query);
            if($result->num_rows>0){
                return $result;
            }else{
                return 0;
            }
        }else{
            return "Error";
        }
    }

    // Función que ejecuta las consultas create, update e insert e imprime un mensaje para verifcar si la consulta fue correcta 
    private function exec_query($query,$message,$errorMessage){
        if ($this->status) {
            if($this->conn->query($query))
                echo $message;       
            else
                echo $errorMessage;       
        }        
    }

    // Función para cerrar la conexión con la base de datos
    public function close_conn(){
        $this->conn->close();
    }
}

// Definición de objetos de la clase usuario
$user1=new Usuarios('mail1@gmail.com','12345'); 
$user2=new Usuarios('mail2@gmail.com','54321'); 

// Uso de la clase Db para el uso de la base de datos
$connection=new Db('localhost','root','','pdo_example'); //Instancia de la clase Db para la conexión con la base de datos
$connection->connect(); //Llamada al método para conectar con la base de datos, debes llamar éste método antes de realizar alguna operación en la base de datos, de lo contrario dará error.



// Uso de los métodos
/* insercción*/
$connection->insert($user1); //Inserta usuario1 en la base de datos

/* Actualización*/
$connection->update_by_id($user2,"where id=59"); //Inserta usuario2 en la base de datos

/* eliminación */
$connection->delete_by_id(5); //Recibe como parametro el id del usuario que se eliminará

/*Selección/*
//$connection->select_all();


/*Selección 2*/
$queryForAll=Db::multiple_select('users','','mail'); //Genera la consulta con el método estatico multiple_select()
$connection->select_2($queryForAll)//Enviamos como parámetro el query generado por la función multiple_select(), solo mostrará todos los mails de los usuarios, ya que el segundo parámetro de la función (la condición) lo dejamos vacío, y solo indicamos que queremos los mails.



?>