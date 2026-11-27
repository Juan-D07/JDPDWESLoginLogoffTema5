-- Creacion de la tabla T02_Departamento
CREATE TABLE IF NOT EXISTS T02_Departamento (
    T02_CodDepartamento VARCHAR(3) PRIMARY KEY,
    T02_DescDepartamento VARCHAR(255) NOT NULL,
    T02_FechaCreacionDepartamento DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    T02_VolumenDeNegocio FLOAT(10,2) NOT NULL,
    T02_FechaBajaDepartamento DATETIME DEFAULT NULL
) ENGINE=InnoDB;

-- Creacion de la tabla T01_Usuario
CREATE TABLE IF NOT EXISTS T01_Usuario (
    T01_CodUsuario VARCHAR(8) PRIMARY KEY,
    T01_Password VARCHAR(255) NOT NULL,
    T01_DescUsuario VARCHAR(255) NOT NULL,
    T01_FechaHoraUltimaConexion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    T01_FechaHoraUltimaConexionAnterior DATETIME DEFAULT NULL,
    T01_NumConexiones INT NOT NULL DEFAULT 1,
    T01_Perfil ENUM('usuario', 'administrador') DEFAULT 'usuario') ENGINE=InnoDB;

-- Insercion de departamentos de prueba
INSERT INTO T02_Departamento (T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio, T02_FechaBajaDepartamento) VALUES
('INF', 'Departamento de Informática', NOW(), 1500.50, NULL),
('CON', 'Departamento de Contabilidad', NOW(), 2500.00, NULL),
('VEN', 'Departamento de Ventas', NOW(), 3500.75, NULL);

-- Insercion de usuarios de prueba
INSERT INTO T01_Usuario (T01_CodUsuario, T01_Password, T01_DescUsuario, T01_FechaHoraUltimaConexion, T01_NumConexiones, T01_Perfil) VALUES
('admin', SHA2(CONCAT('admin', 'paso'), 256), 'Administrador del sistema', NOW(), 0, 'administrador'),
('moises', SHA2(CONCAT('moises', 'paso'), 256), 'Moisés Alberto Domínguez Cruz', NOW(), 0, 'usuario'),
('juanmi', SHA2(CONCAT('juanmi', 'paso'), 256), 'Juan Miguel Dominguez', NOW(), 0, 'usuario'),
('juan', SHA2(CONCAT('juan', 'paso'), 256), 'Juan Rivera', NOW(), 0, 'usuario'),
('yolanda', SHA2(CONCAT('yolanda', 'paso'), 256), 'Yolanda', NOW(), 0, 'usuario'),
('alvaro', SHA2(CONCAT('alvaro', 'paso'), 256), 'Alvaro', NOW(), 0, 'usuario'),
('santiago', SHA2(CONCAT('santiago', 'paso'), 256), 'Santiago', NOW(), 0, 'usuario'),
('heraclio', SHA2(CONCAT('heraclio', 'paso'), 256), 'Heraclio Borbujo', NOW(), 0, 'usuario'),
('amor', SHA2(CONCAT('amor', 'paso'), 256), 'Amor Rodriguez', NOW(), 0, 'usuario');
