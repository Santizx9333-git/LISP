-- Ejecutar una sola vez en la base "lisp"
ALTER TABLE usuarios
    ADD COLUMN email VARCHAR(255) NULL UNIQUE AFTER usuario;

-- Opcional: para volver admin a tu usuario
-- UPDATE usuarios SET rol = 'admin' WHERE usuario = 'tu_usuario';
