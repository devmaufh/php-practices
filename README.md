# Exposición php
## conceptos básicos
### Programación orientada a objetos
Es un paradigma de programación que usa objetos y sus interacciones, para desarrollar cualquier tipo de software. Está basado en varias técnicas, incluyendo herencia, abstracción, polimorfismo y encapsulamiento.

### Clase
Al igual que un diseñador crea prototipos de dispositivos que podrían utilizarse repetidamente para construir dispositivos reales, una clase es un prototipo de software que puede utilizarse para instanciar (Es decir crear) muchos objetos.
### Atributos de clase
Podemos definir variables y asociarlas a una clase (objeto). A esas variables las denominamos atributos. Por ejemplo un alumno, tiene distintos atributos, tales como el nombre, apellidos, edad, etc.
### Funciones o métodos
Una función o método es un proceso asociado a una clase (objeto), éste ejecuta el bloque de código contenido dentro de su declaración. Puede ser llamado por la instancia de un objeto, en el caso de los métodos estáticos éstos si pueden ser invocados sin la necesidad de una instancia.
### Objetos
Un objeto es un ejemplar de una clase. Los objetos pertenecerán siempre a una clase, por lo tanto, dispondrá de los atributos y métodos de la clase a la que pertenece.





## Clase usuarios 
### Atributos
En nuestra clase modelo se definieron un par de atributos
```php
private $email;
private $password;
```

### Constructor
Posteriormente se declaró el constructor, cabe mencionar que siempre que se crea una instancia de cualquier objeto (clase), el código que se encuentra en el constructor es ejecutado de manera automática.<br>En este caso, el constructor recibe como parámetros un e-mail y una contraseña, posteriormente, dentro del bloque del constructor, se asigna el valor de los parámetros a los atributos de la clase que teníamos definidos `$mail` y `$password`.
```php
public function __construct($mail, $password) {
        $this->mail=$mail;
        $this->password=$password;
    }
```
### Métodos de la clase (getters y setters)
Para poder manipular los atributos de la clase se definieron los métodos get y set para cada atributo (como es costumbre cuando se habla de programación orientada a objetos)
<br><br>Los métodos **get** son utilizados para obtener el valor de los atributos cuando hacemos uso de una instancia de la clase.
 ```php
public function get_mail()
    {
        return $this->mail;
    }
public function get_password()
    {
        return $this->password;
    }
 ```
 <br><br>Los métodos **set** nos permiten modificar los valores de los atributos de la clase cuando hacemos uso de una instancia de la clase.
```php
public function set_mail($mail){
        $this->mail=$mail;
    }
public function set_password($pass)
    {
        $this->password=$pass;
    } 
```

## Clase Db
Para hacer uso de esta clase es necesario crear una base de datos, para éste ejemplo la base de datos fue llamada `example_pdo`, dentro de ésta se creó una tabla llamada `users` con los campos `mail` y `password`.

### Atributos
Para hacer uso de ésta clase se necesitan unos cuantos atributos, como el nombre del servidor, nombre de usuario de la base de datos, contraseña del usuario, nombre de la base de datos que se usará, una variable para gestionar y usar la conexión con la base de datos y una variable pública para verificar el status de la conexión.
```php
   # Atributos de la clase
    protected $servername;
    protected $username;
    protected $password;
    protected $dbName;
    protected $conn;
    public $status;
```

### Constructor
En nuestro constructor recibimos de parametros los nombres del servidor, usuario y de la base de datos, además de la contraseña del usuario. Para después asignar a los atributos de la clase el valor de los parámetros requeridos en el constructor. De esta manera podremos inicializar una instancia de la clase `mysqli` para poder trabajar con la base de datos.

```php
//Constructor de la clase
public function __construct($servername,$username,$password,$dbName) {
        $this->servername=$servername;
        $this->username=$username;
        $this->password=$password;
        $this->dbName=$dbName;
    }
```
### Métodos de la clase
#### Connect
Éste método sirve para crear la instancia de la conexión a la base de datos, es aquí donde se hace uso del atributo `$conn` declarado en la cabecera de la clase. 

Primero creamos una nueva instancia de la clase `mysqli` a la que le pasamos como parámetros el nombre del servidor, nombre de usuario, contraseña y nombre de la base de datos.
```php
$this->conn=new mysqli($this->servername,$this->username $this->password,$this->dbName);
```
Posteriormene verificamos si la conexión fue realizada correctamente, para ello, hacemos uso de la variable estática perteneciente a la clase `mysqli` llamada `connect_error` que nos devuelve una cadena (String) en caso de que exista algún error al realizar la conexión.
<br>Si **no** existe error alguno, asignamos el valor `TRUE` a nuestro atributo `status` y si existe hay algún error, asignamos el valor `FALSE`.
```php
        if($this->conn->connect_error){
            $this->status=FALSE;
        }else{
            $this->status=TRUE;
        }
```
Este es el código completo de la función **connect**

```php
public function connect(){
        $this->conn=new mysqli($this->servername,$this->username,$this->password,$this->dbName);
        if($this->conn->connect_error){
           // die("Conexión fallida".$this->conn->connect_error);
            $this->status=FALSE;
        }else{
            $this->status=TRUE;
        }
    }
```
Cabe mencionar que antes de hacer alguna operación en la base de datos, debemos llamar a éste método para evitar errores.

#### Insert
Para realizar una insercción en la base de datos, hacemos uso del método **insert**. Recibe como parámetro un objeto **usuario** de la clase **Usuarios** que creamos anteriormente.

Generamos una consulta **sql** para insertar en los campos `mail`y `password` los atributos `mail` y `password` del objeto **usuario** que recibió como parámetro el método.
```php
$query="insert into users(mail,password) values('".$user->get_mail()."','".$user->get_password()."')";
```
Ahora, hacemos una llamada al método **exec_query** (Que se explicará a continuación) pasándole de parámetros la consulta (```$query```) creado, un mensaje que mostrará en caso de que la consulta sea exitosa y finalmente otro mensaje en caso de que haya algún error en el proceso.
```php
$this->exec_query($query,"Registro insertado correctamente","Error al insertar registro");    
```
Código completo de la función **insert**
 ```php
public function insert($user)
    {
        $query="insert into users(mail,password) values('".$user->get_mail()."','".$user->get_password()."')";
        $this->exec_query($query,"Registro insertado correctamente","Error al insertar registro");    
    }
 ```

 #### exec_query
 El método **exec_query** fue diseñado para ejecutar las consultas que reciba como parámetro. primero verifica que la conexión con la base de datos sea correcta a través del atributo `status`, si `status` es `TRUE` entonces ejecuta el método `query` que recibe como parámetro la consulta que queremos ejecutar. Si la consulta fue correcta muestra el mensaje que recibe de parámetro, si por algún motivo hay algún error en la ejecución de la consulta, mostrará el mensaje de error que recibe el método por parámetro.

 ```php
private function exec_query($query,$message,$errorMessage){
        if ($this->status) {
            if($this->conn->query($query))
                echo $message;       
            else
                echo $errorMessage;       
        }        
    }
 ```
Este método es privado porque solo queremos que sea accesible dentro de la misma clase, si quisieramos que sea accesible a través de una instancia de la clase, deberíamos hacerlo público como los métodos **insert**,**delete_by_id**,**connect**,**update**, etc.
#### delete_by_id
Este metodo es muy similar al método **insert**, solo que éste recibe de parámetro el **id** del usuario que se desea eliminar de la base de datos.
Al igual que en el método insert, generamos una consulta **sql**.
```php
$query="delete from users where id=".$id;
```
Una de las bondades de la programación orientada a objetos, es reutilizar código a través de funciones o métodos. En éste caso hacemos uso nuevamente del método **exec_query** para ejecutar nuestra consulta.
```php
$this->exec_query($query,"Registro eliminado correctamente","Error al eliminar el registro");    
```
Código completo del método **delete_by_id**

```php
public function delete_by_id($id)
    {
        $query="delete from users where id=$id";
        $this->exec_query($query,"Registro eliminado correctamente","Error al eliminar el registro");    
    }
```

#### update_by_id
Este método no difiere mucho del insert, solamente requiere un parámetro más que es la condición, én éste caso, queremos actualizar los usuarios a través del **id**.
<br>Construimos una consulta:
```php
$query="update users set mail='".$user->get_mail()."',password='".$user->get_password()."' ".$condition;
```
Y le concatenamos la condición para evitar que no se actualicen todos los registros de nuestra base de datos.

Hacemos uso nuevamente del método **exec_query** y le pasamos de parámetros la consulta y los mensajes que queremos que se muestren
```php
$this->exec_query($query,"Registro actualizado correctamente","Error al actualizar");   
```

Código completo del método
```php
public function update_by_id($user,$condition)
    {
        $query="update users set mail='".$user->get_mail()."',password='".$user->get_password()."' ".$condition;
        $this->exec_query($query,"Registro actualizado correctamente","Error al actualizar");   
    }
```
