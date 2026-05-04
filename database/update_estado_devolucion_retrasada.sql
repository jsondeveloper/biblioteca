USE biblioteca;

ALTER TABLE prestamos
    MODIFY estado ENUM('Activo','Devuelto','Retrasado','Devolucion Retrasada') NOT NULL DEFAULT 'Activo';

ALTER TABLE prestamos_historial
    MODIFY estado_anterior ENUM('Activo','Devuelto','Retrasado','Devolucion Retrasada') NOT NULL,
    MODIFY estado_nuevo ENUM('Activo','Devuelto','Retrasado','Devolucion Retrasada') NOT NULL;

UPDATE prestamos
SET estado = 'Devolucion Retrasada'
WHERE estado IN ('Devuelto','Retrasado')
  AND fecha_entrega IS NOT NULL
  AND fecha_devolucion IS NOT NULL
  AND fecha_entrega > fecha_devolucion;
