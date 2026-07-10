USE Sportx;

CREATE TABLE Usuarios(
id_usuarios INT AUTO_INCREMENT;
nombre VARCHAR(100) NOT NULL;
correo VARCHAR(250) NOT NULL UNIQUE;
password_hash VARCHAR(250) NOT NULL;

RolENUM(
    'usuario'
    'admin'
)DEFAULT 'usuario'
)
fecha_registro          DATETIME DEFAULT CURRENT_TIMESTAMP 7

estado ENUM(
    'activo'
    'suspendido'
)DEFAULT 'activo'

CREATE TABLE interses_usuarios


