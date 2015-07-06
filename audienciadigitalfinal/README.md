Audiência Digital
============================

Projeto de audiência digital CNJ


Requerimentos
------------

Feito em yii basta abrir o arquivo requeriments.php


Instalação
------------
Baixar o projeto, dar um composer update para baixar as dependencias e rodar o banco.



CONFIGURAÇÃO
-------------

### Database

Editar o arquivo `config/db.php`:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=audiencia_digital',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];

