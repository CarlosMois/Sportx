USE Sportx;

CREATE TABLE Usuarios(
id_usuarios INT AUTO_INCREMENT,
nombre VARCHAR(100) NOT NULL,
correo VARCHAR(250) NOT NULL UNIQUE,
password_hash VARCHAR(250) NOT NULL 

RolENUM(
    'usuario'
    'admin'
)DEFAULT 'usuario'
);
fecha_registro          DATETIME DEFAULT CURRENT_TIMESTAMP 7

estado ENUM(
    'activo'
    'suspendido'
)DEFAULT 'activo'

CREATE TABLE interses_usuarios(
    id_intereses INT NOT NULL PRIMARY KEY,
    id_usuarios INT NOT NULL,
    id_deporte INT NOT NULL
);

CREATE TABLE Favoritos(
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_centro INT NOT NULL,
    fecha_agregado DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE centros_deportivos(
    id_centro INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    direccion VARCHAR(255),
    departamento VARCHAR(150) NOT NULL,
    municipio VARCHAR(150) NOT NULL,
    tlefono VARCHAR(20),
    correo VARCHAR(150)
);
CREATE TABLE deportes(
 id_deporte INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(100) NOT NULL UNIQUE,
 descripcion TEXT NULL,
 icono VARCHAR(255) NULL
);
 
CREATE TABLE centro_deporte(
 id_centro_deporte INT AUTO_INCREMENT PRIMARY KEY,
 id_centros_deportivos INT NOT NULL
 id_deporte INT NOT NULL
);
 
CREATE TABLE costo_centro(
 id_costo INT AUTO_INCREMENT PRIMARY KEY,
 id_centro_deporte INT NOT NULL,
 nombre_costo VARCHAR(150) NOT NULL
 precio DECIMAL (10,2) NOT NULL
 
 unidad ENUM(
    'por_hora',
    'por_clase',
    'mensual',
    'uso de cancha'
 ) NOT NULL
);
 


