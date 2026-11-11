USE DBJDPDWESProyectoTema5;

INSERT INTO T02_Departamento VALUES
('VTA', 'Departamento de Ventas y Comercialización', '2015-01-15 09:00:00', 1250500.75, NULL),
('RCH', 'Recursos Humanos y Talento', '2015-01-15 09:00:00', 45000.00, NULL),
('ING', 'Ingeniería y Desarrollo de Software', '2018-06-01 10:30:00', 850200.50, NULL),
('FIN', 'Finanzas, Contabilidad y Tesorería', '2015-01-15 09:00:00', 320000.00, NULL),
('MKT', 'Marketing Digital', '2019-02-20 08:15:00', 150000.25, '2025-12-31 18:00:00');

INSERT INTO T01_Usuario  VALUES
('admin', SHA2('adminpaso', 256), 'Administrador del sistema', null, 0, 'admin'),
('moises', SHA2('moisespaso', 256), 'Moisés Alberto Domínguez Cruz', null, 0, 'usuario'),
('juan', SHA2('juanpaso', 256), 'Juan Miguel Dominguez Perdigon', null, 0, 'usuario'),
('heraclio', SHA2('heracliopaso', 256), 'Heraclio', null, 0, 'usuario'),
('amor', SHA2('amorpaso', 256), 'amor <3', null, 0, 'usuario');