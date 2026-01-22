-- 1. Create Clientes Table
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dni` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dni` (`dni`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Modify Asignaciones Table
-- We need to check if columns exist before adding them to avoid errors on re-run, 
-- but straightforward ALTER IGNORE or simply running and handling error is easier in raw SQL scripts.
-- For safety, will use a stored procedure approach for idempotency or just try benign ALTERs.

-- Allow id_empleado to be NULL
ALTER TABLE `asignaciones` MODIFY `id_empleado` int NULL;

-- Add id_cliente column
-- (Primitive "Iterempotent" check: Try to add, if fails it exists. But MySQL doesn't have IF NOT EXISTS for columns in 5.7+ easily without procedure. 
-- Since this is a dev/controlled env, I will just run ADD COLUMN. If it fails, I'll ignore or checking manual state)
SET @dbname = DATABASE();
SET @tablename = "asignaciones";
SET @columnname = "id_cliente";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 1",
  "ALTER TABLE asignaciones ADD COLUMN id_cliente int DEFAULT NULL AFTER id_empleado"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add Foreign Key for id_cliente
-- Similarly, blindly adding FK might fail if exists. 
-- I'll assume standard clean run or just run it. 
-- If it fails due to existing name, it's fine.
-- Let's try to add it.
SET @preparedStatementFK = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
    WHERE
      (table_name = 'asignaciones')
      AND (table_schema = @dbname)
      AND (constraint_name = 'fk_cliente_asignado')
  ) > 0,
  "SELECT 1",
  "ALTER TABLE asignaciones ADD CONSTRAINT fk_cliente_asignado FOREIGN KEY (id_cliente) REFERENCES clientes (id) ON UPDATE CASCADE"
));
PREPARE addFK FROM @preparedStatementFK;
EXECUTE addFK;
DEALLOCATE PREPARE addFK;


-- 3. Add Role 'Empleado'
INSERT IGNORE INTO `roles` (`nombre_rol`) VALUES ('Empleado');
