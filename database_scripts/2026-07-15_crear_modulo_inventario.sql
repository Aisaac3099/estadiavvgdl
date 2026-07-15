-- Script seguro para crear la primera versión del módulo Inventario.
-- Usar una sola opción para actualizar la base real: php spark migrate O este SQL manual.
-- No ejecutar ambas opciones si las tablas ya fueron creadas.

CREATE TABLE IF NOT EXISTS `inventario` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL,
  `alias` VARCHAR(150) NULL,
  `marca` VARCHAR(100) NULL,
  `modelo` VARCHAR(100) NULL,
  `cantidad` INT UNSIGNED NOT NULL DEFAULT 0,
  `bodega` INT UNSIGNED NOT NULL,
  `anaquel` INT UNSIGNED NOT NULL,
  `nivel` INT UNSIGNED NOT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `inventario_fotos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `inventario_id` BIGINT UNSIGNED NOT NULL,
  `foto` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `inventario_fotos_inventario_id_foreign` (`inventario_id`),
  CONSTRAINT `inventario_fotos_inventario_id_foreign`
    FOREIGN KEY (`inventario_id`) REFERENCES `inventario` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
