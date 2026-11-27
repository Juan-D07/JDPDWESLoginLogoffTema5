USE DBJDPDWESLoginLogoffTema5;

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

