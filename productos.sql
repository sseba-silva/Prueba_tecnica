CREATE DATABASE IF NOT EXISTS productos_db;
USE productos_db;

-- Tabla de Bodegas
CREATE TABLE IF NOT EXISTS Bodegas (
    id_bodega INT AUTO_INCREMENT PRIMARY KEY,
    nombre_bodega VARCHAR(100) NOT NULL
);

-- Tabla de Sucursales
CREATE TABLE IF NOT EXISTS Sucursales (
    id_sucursal INT AUTO_INCREMENT PRIMARY KEY,
    id_bodega INT NOT NULL,
    nombre_sucursal VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_bodega) REFERENCES Bodegas(id_bodega) ON DELETE CASCADE
);

-- Tabla de Monedas
CREATE TABLE IF NOT EXISTS Monedas (
    id_moneda INT AUTO_INCREMENT PRIMARY KEY,
    nombre_moneda VARCHAR(50) NOT NULL
);

-- Tabla de Productos
CREATE TABLE IF NOT EXISTS Productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(15) NOT NULL UNIQUE,
    nombre_producto VARCHAR(50) NOT NULL,
    id_bodega INT NOT NULL,
    id_sucursal INT NOT NULL,
    id_moneda INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    descripcion TEXT NOT NULL,
    FOREIGN KEY (id_bodega) REFERENCES Bodegas(id_bodega) ON DELETE CASCADE,
    FOREIGN KEY (id_sucursal) REFERENCES Sucursales(id_sucursal) ON DELETE CASCADE,
    FOREIGN KEY (id_moneda) REFERENCES Monedas(id_moneda) ON DELETE CASCADE
);

-- Tabla de Materiales
CREATE TABLE IF NOT EXISTS Materiales (
    id_material INT AUTO_INCREMENT PRIMARY KEY,
    nombre_material VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla de relación Producto-Material
CREATE TABLE IF NOT EXISTS ProductoMaterial (
    id_producto INT NOT NULL,
    id_material INT NOT NULL,
    PRIMARY KEY (id_producto, id_material),
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (id_material) REFERENCES Materiales(id_material) ON DELETE CASCADE
);

-- Insertar valores de ejemplo en la tabla de Materiales
INSERT INTO Materiales (nombre_material) VALUES 
('Plastico'), ('Metal'), ('Madera'), ('Vidrio'), ('Textil');

-- Insertar valores de ejemplo en la tabla de Monedas
INSERT INTO Monedas (nombre_moneda) VALUES 
('Dólar'), ('Euro'), ('Peso');

-- Insertar valores de ejemplo en la tabla de Bodegas
INSERT INTO Bodegas (nombre_bodega) VALUES
('Bodega 1'),
('Bodega 2'),
('Bodega 3');

-- Poblar la tabla Sucursales
INSERT INTO Sucursales (id_bodega, nombre_sucursal) VALUES
(1, 'Sucursal 1'),
(1, 'Sucursal 2'),
(2, 'Sucursal 3'),
(2, 'Sucursal 4'),
(3, 'Sucursal 5'),
(3, 'Sucursal 6');

