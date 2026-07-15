-- Script seguro para producción: crea únicamente la tabla de lecturas por usuario.
-- No modifica datos existentes de notificaciones ni usuarios.
-- Si ya ejecutaste php spark migrate y la tabla existe, no ejecutes este script nuevamente.

CREATE TABLE IF NOT EXISTS notificaciones_leidas (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    notificacion_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    leida_at DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_notificacion_usuario (notificacion_id, usuario_id),
    KEY idx_notificacion_id (notificacion_id),
    KEY idx_usuario_id (usuario_id)
);
