-- Script manual seguro para producción.
-- Primero verifica si las columnas existen. Si alguna no existe, ejecuta solo el ALTER correspondiente.

SELECT COLUMN_NAME
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'servicios_autos'
  AND COLUMN_NAME IN ('estado_servicio', 'observacion_estado');

-- Ejecutar solo si NO existe la columna estado_servicio:
ALTER TABLE servicios_autos
ADD COLUMN estado_servicio ENUM('material_comprado','en_proceso','realizado','cancelado') NULL DEFAULT NULL AFTER tipo_registro;

-- Ejecutar solo si NO existe la columna observacion_estado:
ALTER TABLE servicios_autos
ADD COLUMN observacion_estado TEXT NULL AFTER estado_servicio;

-- SQL opcional NO ejecutado para revisar valores con error tipográfico:
SELECT id, estado_servicio
FROM servicios_autos
WHERE estado_servicio = 'reaizado';

-- SQL opcional NO ejecutado para corregir el error tipográfico sin borrar registros.
-- Úsalo solo si el SELECT anterior devuelve filas y la columna actual permite ese valor temporalmente.
-- UPDATE servicios_autos
-- SET estado_servicio = 'realizado'
-- WHERE estado_servicio = 'reaizado';

-- SQL opcional NO ejecutado para inicializar históricos.
-- ADVERTENCIA: marcará como realizados todos los registros únicos y periódicos históricos sin estado.
-- UPDATE servicios_autos
-- SET estado_servicio = NULL,
--     observacion_estado = NULL
-- WHERE tipo_registro = 'por_asignar';
--
-- UPDATE servicios_autos
-- SET estado_servicio = 'realizado'
-- WHERE tipo_registro <> 'por_asignar'
--   AND estado_servicio IS NULL;
