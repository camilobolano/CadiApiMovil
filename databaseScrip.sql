CREATE TABLE IF NOT EXISTS usuario (
                                       nombre VARCHAR(50) NOT NULL,
    documento VARCHAR(20) PRIMARY KEY NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL,
    contrasenia TEXT  NOT NULL
    );

INSERT INTO usuario (nombre, documento, apellido, usuario, contrasenia)
VALUES ('Andres', '1060587849', 'Guerrero', 'dixon', 'password');


SELECT * FROM usuario