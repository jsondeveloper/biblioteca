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

-- Datos de prueba ampliados con todos los estados posibles
INSERT INTO usuarios (username, password, email, role) VALUES
('estudiante01', '$2y$10$kX58rZHtRbAj2q39UN.kLe91B1I2WAhQzUlO6qhGrT4o3yCCPQTCW', 'estudiante01@uni.edu', 'estudiante'),
('estudiante02', '$2y$10$kX58rZHtRbAj2q39UN.kLe91B1I2WAhQzUlO6qhGrT4o3yCCPQTCW', 'estudiante02@uni.edu', 'estudiante'),
('estudiante03', '$2y$10$kX58rZHtRbAj2q39UN.kLe91B1I2WAhQzUlO6qhGrT4o3yCCPQTCW', 'estudiante03@uni.edu', 'estudiante'),
('estudiante04', '$2y$10$kX58rZHtRbAj2q39UN.kLe91B1I2WAhQzUlO6qhGrT4o3yCCPQTCW', 'estudiante04@uni.edu', 'estudiante'),
('estudiante05', '$2y$10$kX58rZHtRbAj2q39UN.kLe91B1I2WAhQzUlO6qhGrT4o3yCCPQTCW', 'estudiante05@uni.edu', 'estudiante'),
('bibliotecario01', '$2y$10$zqED4hhl42PjmuI/h/f6e.DycW1Zm0rRznZtp.nxDxl4vAWNtzdbq', 'bibliotecario01@uni.edu', 'bibliotecario'),
('bibliotecario02', '$2y$10$zqED4hhl42PjmuI/h/f6e.DycW1Zm0rRznZtp.nxDxl4vAWNtzdbq', 'bibliotecario02@uni.edu', 'bibliotecario');

INSERT INTO estudiantes (usuario_id, nombre, telefono) VALUES
(1, 'Ana Gomez', '555-0101'),
(2, 'Diego Fernandez', '555-0102'),
(3, 'Laura Martinez', '555-0103'),
(4, 'Carlos Rodriguez', '555-0104'),
(5, 'Sofia Lopez', '555-0105');

INSERT INTO bibliotecarios (usuario_id, nombre) VALUES
(6, 'Carla Mendez'),
(7, 'Roberto Sanchez');

INSERT INTO categorias (nombre, descripcion) VALUES
('Ciencias', 'Libros de ciencias exactas y naturales'),
('Literatura', 'Novelas, cuentos y obras literarias'),
('Tecnologia', 'Textos de informatica y tecnologia moderna'),
('Historia', 'Libros de historia universal y local'),
('Matematicas', 'Textos de matematicas y logica'),
('Fisica', 'Libros de fisica teorica y aplicada'),
('Quimica', 'Textos de quimica organica e inorganica'),
('Medicina', 'Libros de medicina y salud'),
('Derecho', 'Textos juridicos y legales'),
('Economia', 'Libros de economia y finanzas');

INSERT INTO libros (titulo, isbn, autor, categoria_id, estado, anio_publicacion, descripcion) VALUES
-- Libros Disponibles
('Fundamentos de Programacion', '978-1234567890', 'Jorge Perez', 3, 'Disponible', 2021, 'Libro base para aprender programacion.'),
('Introduccion a la Algebra', '978-1111111111', 'Maria Gonzalez', 5, 'Disponible', 2020, 'Conceptos basicos de algebra lineal.'),
('Historia Universal', '978-2222222222', 'Pedro Ramirez', 4, 'Disponible', 2018, 'Panorama completo de la historia mundial.'),
('Quimica Organica', '978-3333333333', 'Ana Silva', 7, 'Disponible', 2019, 'Principios de quimica organica.'),
('Economia Basica', '978-4444444444', 'Luis Torres', 10, 'Disponible', 2022, 'Introduccion a los principios economicos.'),

-- Libros Reservados
('Estructuras de Datos', '978-0987654321', 'Maria Ruiz', 3, 'Reservado', 2022, 'Guia practica de estructuras de datos.'),
('Cien años de soledad', '978-0307474728', 'Gabriel Garcia Marquez', 2, 'Reservado', 1967, 'Clasico de la literatura latinoamericana.'),
('Fisica Moderna', '978-5555555555', 'Albert Einstein', 6, 'Reservado', 1916, 'Teoria de la relatividad y fisica cuantica.'),
('Derecho Constitucional', '978-6666666666', 'Fernando Castro', 9, 'Reservado', 2021, 'Principios del derecho constitucional.'),

-- Libros Prestados
('Biologia Celular', '978-7777777777', 'Ana Lopez', 1, 'Prestado', 2019, 'Introduccion a la biologia celular.'),
('El Amor en los Tiempos del Colera', '978-8888888888', 'Gabriel Garcia Marquez', 2, 'Prestado', 1985, 'Novela romantica del nobel colombiano.'),
('Calculo Diferencial', '978-9999999999', 'Roberto Vargas', 5, 'Prestado', 2023, 'Fundamentos del calculo diferencial.'),
('Microeconomia', '978-1010101010', 'Carmen Diaz', 10, 'Prestado', 2021, 'Teoria microeconomica avanzada.'),

-- Libros en Mantenimiento
('Redes de Computadoras', '978-1111111112', 'Miguel Angel', 3, 'Mantenimiento', 2020, 'Guia completa de redes informaticas.'),
('Literatura Medieval', '978-1212121212', 'Isabel Fernandez', 2, 'Mantenimiento', 2017, 'Estudio de la literatura medieval europea.'),
('Termodinamica', '978-1313131313', 'Jorge Martinez', 6, 'Mantenimiento', 2018, 'Principios de termodinamica aplicada.');

INSERT INTO prestamos (libro_id, estudiante_id, bibliotecario_id, fecha_prestamo, fecha_devolucion, fecha_entrega, estado, observaciones) VALUES
-- Prestamos Activos
(10, 1, 1, '2026-04-15', '2026-05-15', NULL, 'Activo', 'Prestamo de curso de biologia.'),
(11, 2, 1, '2026-04-10', '2026-05-10', NULL, 'Activo', 'Lectura recreativa.'),
(12, 3, 2, '2026-04-12', '2026-05-12', NULL, 'Activo', 'Estudio de calculo.'),
(13, 4, 2, '2026-04-08', '2026-05-08', NULL, 'Activo', 'Investigacion economica.'),

-- Prestamos Devueltos
(2, 1, 1, '2026-03-10', '2026-04-10', '2026-04-09', 'Devuelto', 'Devuelto antes de la fecha limite.'),
(3, 2, 1, '2026-03-15', '2026-04-15', '2026-04-14', 'Devuelto', 'Libro en excelentes condiciones.'),
(14, 3, 2, '2026-03-20', '2026-04-20', '2026-04-18', 'Devuelto', 'Devuelto con algunos subrayados.'),
(15, 4, 2, '2026-03-25', '2026-04-25', '2026-04-22', 'Devuelto', 'Lectura completada exitosamente.'),

-- Prestamos Retrasados
(16, 5, 1, '2026-03-01', '2026-04-01', NULL, 'Retrasado', 'Atraso en devolucion - notificado.'),
(10, 1, 2, '2026-02-15', '2026-03-15', NULL, 'Retrasado', 'Segundo aviso de retraso enviado.'),
(11, 2, 1, '2026-02-20', '2026-03-20', NULL, 'Retrasado', 'Requiere renovacion o devolucion inmediata.');

INSERT INTO reservas (libro_id, estudiante_id, fecha_reserva, fecha_expiracion, estado) VALUES
-- Reservas Activas
(6, 1, '2026-04-18', '2026-04-25', 'Activa'),
(7, 2, '2026-04-20', '2026-04-27', 'Activa'),
(8, 3, '2026-04-22', '2026-04-29', 'Activa'),
(9, 4, '2026-04-24', '2026-05-01', 'Activa'),

-- Reservas Cumplidas
(1, 2, '2026-04-01', '2026-04-08', 'Cumplida'),
(4, 3, '2026-04-05', '2026-04-12', 'Cumplida'),
(5, 4, '2026-04-10', '2026-04-17', 'Cumplida'),
(12, 5, '2026-04-15', '2026-04-22', 'Cumplida'),

-- Reservas Canceladas
(2, 1, '2026-04-02', '2026-04-09', 'Cancelada'),
(3, 2, '2026-04-07', '2026-04-14', 'Cancelada'),
(14, 3, '2026-04-12', '2026-04-19', 'Cancelada');

INSERT INTO prestamos_historial (prestamo_id, estado_anterior, estado_nuevo, cambiado_por, comentario) VALUES
-- Historial de cambios de estado
(1, 'Activo', 'Retrasado', 6, 'Se genero una notificacion de atraso por email.'),
(2, 'Activo', 'Devuelto', 6, 'El libro se entrego en buen estado.'),
(3, 'Activo', 'Devuelto', 7, 'Devolucion anticipada - estudiante cumplio con el curso.'),
(4, 'Activo', 'Devuelto', 6, 'Libro devuelto con anotaciones de estudio.'),
(5, 'Activo', 'Retrasado', 7, 'Primer aviso de retraso enviado.'),
(6, 'Activo', 'Retrasado', 6, 'Segundo aviso - requiere devolucion inmediata.'),
(7, 'Retrasado', 'Devuelto', 7, 'Libro devuelto despues de renovacion especial.'),
(8, 'Activo', 'Devuelto', 6, 'Devolucion exitosa - estudiante graduado.');

INSERT INTO sanciones (estudiante_id, razon, fecha_inicio, fecha_fin, activa) VALUES
(5, 'Devolucion tardia de libro de fisica', '2026-04-01', '2026-04-15', TRUE),
(1, 'Daño a libro de literatura', '2026-03-15', '2026-03-30', FALSE),
(2, 'Tres prestamos retrasados en el ultimo mes', '2026-04-10', '2026-04-25', TRUE),
(3, 'Perdida temporal de libro', '2026-03-01', '2026-03-15', FALSE);
