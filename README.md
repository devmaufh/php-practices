# Prácticas php

## Clase usuarios 
### Atributos
En nuestra clase modelo se definieron un par de atributos
```php
private $email;
private $password;
```

### Constructor
Posteriormente se declaró el constructor, cabe mencionar que siempre que se crea una instancia de cualquier objeto (clase), el código que se encuentra en el constructor es ejecutado de manera automática.<br>En ete caso, el constructor recibe como parámetros un e-mail y una contraseña, posteriormente, dentro del bloque del constructor, se asigna el valor de los parámetros a los atributos de la clase que teníamos definidos `$mail` y `$password`.
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