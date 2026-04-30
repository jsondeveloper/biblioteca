-- Crea la base de datos y todas las tablas necesarias para la biblioteca universitaria
CREATE DATABASE IF NOT EXISTS biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    role ENUM('estudiante','bibliotecario') NOT NULL DEFAULT 'estudiante',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    telefono VARCHAR(30) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS bibliotecarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL UNIQUE,
    descripcion TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    isbn VARCHAR(30) NOT NULL UNIQUE,
    autor VARCHAR(200) NOT NULL,
    categoria_id INT NOT NULL,
    estado ENUM('Disponible','Reservado','Prestado','Mantenimiento') NOT NULL DEFAULT 'Disponible',
    anio_publicacion YEAR NULL,
    descripcion TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libro_id INT NOT NULL,
    estudiante_id INT NOT NULL,
    bibliotecario_id INT NOT NULL,
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion DATE NULL,
    fecha_entrega DATE NULL,
    estado ENUM('Activo','Devuelto','Retrasado') NOT NULL DEFAULT 'Activo',
    observaciones TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE RESTRICT,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON DELETE CASCADE,
    FOREIGN KEY (bibliotecario_id) REFERENCES bibliotecarios(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libro_id INT NOT NULL,
    estudiante_id INT NOT NULL,
    fecha_reserva DATE NOT NULL,
    fecha_expiracion DATE NOT NULL,
    estado ENUM('Activa','Cumplida','Cancelada') NOT NULL DEFAULT 'Activa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE RESTRICT,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS prestamos_historial (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prestamo_id INT NOT NULL,
    estado_anterior ENUM('Activo','Devuelto','Retrasado') NOT NULL,
    estado_nuevo ENUM('Activo','Devuelto','Retrasado') NOT NULL,
    cambiado_por INT NULL,
    comentario TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prestamo_id) REFERENCES prestamos(id) ON DELETE CASCADE,
    FOREIGN KEY (cambiado_por) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sanciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    razon VARCHAR(255) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    activa BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Datos de prueba
INSERT INTO usuarios (username, password, email, role) VALUES
('estudiante01', '$2y$10$kX58rZHtRbAj2q39UN.kLe91B1I2WAhQzUlO6qhGrT4o3yCCPQTCW', 'estudiante01@uni.edu', 'estudiante'),
('estudiante02', '$2y$10$kX58rZHtRbAj2q39UN.kLe91B1I2WAhQzUlO6qhGrT4o3yCCPQTCW', 'estudiante02@uni.edu', 'estudiante'),
('bibliotecario01', '$2y$10$zqED4hhl42PjmuI/h/f6e.DycW1Zm0rRznZtp.nxDxl4vAWNtzdbq', 'bibliotecario01@uni.edu', 'bibliotecario');

INSERT INTO estudiantes (usuario_id, nombre, telefono) VALUES
(1, 'Ana Gomez', '555-0101'),
(2, 'Diego Fernandez', '555-0102');

INSERT INTO bibliotecarios (usuario_id, nombre) VALUES
(3, 'Carla Mendez');

INSERT INTO categorias (nombre, descripcion) VALUES
('Ciencias', 'Libros de ciencias exactas y naturales'),
('Literatura', 'Novelas, cuentos y obras literarias'),
('Tecnologia', 'Textos de informatica y tecnologia moderna');

INSERT INTO libros (titulo, isbn, autor, categoria_id, estado, anio_publicacion, descripcion) VALUES
('Fundamentos de Programacion', '978-1234567890', 'Jorge Perez', 3, 'Disponible', 2021, 'Libro base para aprender programacion.'),
('Estructuras de Datos', '978-0987654321', 'Maria Ruiz', 3, 'Prestado', 2022, 'Guia practica de estructuras de datos.'),
('Cien anos de soledad', '978-0307474728', 'Gabriel Garcia Marquez', 2, 'Reservado', 1967, 'Clasico de la literatura latinoamericana.'),
('Biologia Celular', '978-1111111111', 'Ana Lopez', 1, 'Mantenimiento', 2019, 'Introduccion a la biologia celular.');

INSERT INTO prestamos (libro_id, estudiante_id, bibliotecario_id, fecha_prestamo, fecha_devolucion, fecha_entrega, estado, observaciones) VALUES
(2, 1, 1, '2026-04-15', '2026-05-15', NULL, 'Activo', 'Prestamo de curso.'),
(3, 2, 1, '2026-03-10', '2026-04-10', '2026-04-09', 'Devuelto', 'Devuelto antes de la fecha.');

INSERT INTO reservas (libro_id, estudiante_id, fecha_reserva, fecha_expiracion, estado) VALUES
(3, 1, '2026-04-18', '2026-04-25', 'Activa'),
(1, 2, '2026-04-20', '2026-04-27', 'Activa');

INSERT INTO prestamos_historial (prestamo_id, estado_anterior, estado_nuevo, cambiado_por, comentario) VALUES
(1, 'Activo', 'Retrasado', 2, 'Se genero una notificacion de atraso.'),
(2, 'Activo', 'Devuelto', 2, 'El libro se entrego en buen estado.');
