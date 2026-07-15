CREATE DATABASE Sportx;
USE Sportx;
 
CREATE TABLE deportes(
    id_deporte INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT NULL,
    icono VARCHAR(255) NULL
);
 
CREATE TABLE Usuarios(
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(250) NOT NULL UNIQUE,
    password_hash VARCHAR(250) NOT NULL,
    rol ENUM('usuario', 'admin') DEFAULT 'usuario',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo', 'suspendido') DEFAULT 'activo'
);
 
CREATE TABLE intereses_usuarios(
    id_interes INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_deporte INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_deporte) REFERENCES deportes(id_deporte)
);
 
CREATE TABLE centros_deportivos(
    id_centro INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    direccion VARCHAR(255),
    departamento VARCHAR(150) NOT NULL,
    municipio VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(150)
);
 
CREATE TABLE Favoritos(
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_centro INT NOT NULL,
    fecha_agregado DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_centro) REFERENCES centros_deportivos(id_centro) ON DELETE CASCADE
);
 
CREATE TABLE centro_deporte(
    id_centro_deporte INT AUTO_INCREMENT PRIMARY KEY,
    id_centro INT NOT NULL,
    id_deporte INT NOT NULL,
    FOREIGN KEY (id_centro) REFERENCES centros_deportivos(id_centro) ON DELETE CASCADE,
    FOREIGN KEY (id_deporte) REFERENCES deportes(id_deporte) ON DELETE CASCADE
);
 
CREATE TABLE costo_centro(
    id_costo INT AUTO_INCREMENT PRIMARY KEY,
    id_centro_deporte INT NOT NULL,
    nombre_costo VARCHAR(150) NOT NULL,
    precio DECIMAL (10,2) NOT NULL,
    unidad ENUM('por_hora', 'por_clase', 'mensual', 'uso de cancha') NOT NULL,
    FOREIGN KEY (id_centro_deporte) REFERENCES centro_deporte(id_centro_deporte) ON DELETE CASCADE
);
 
CREATE TABLE horarios(
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    id_centro INT NOT NULL,
    dia_semana ENUM('lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'),
    hora_apertura TIME,
    hora_cierre TIME,
    FOREIGN KEY (id_centro) REFERENCES centros_deportivos(id_centro) ON DELETE CASCADE
);
 
CREATE TABLE imagenes (
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,
    id_centro INT NOT NULL,
    imagen_url VARCHAR(255),
    FOREIGN KEY (id_centro) REFERENCES centros_deportivos(id_centro)
    );
    CREATE TABLE resenas(
    id_resena INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_centro INT NOT NULL,
    calificacion TINYINT NOT NULL,
    comentario TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
    );