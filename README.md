# 📘 Manual de Usuario - Sistema de Inventario TI

Bienvenido al sistema de **Gestión de Inventario TI**. Este documento es una guía paso a paso para administrar el ciclo de vida de los activos tecnológicos de la empresa, desde su ingreso hasta su baja, incluyendo asignaciones a empleados.

---

## 🚀 Paso 1: Acceso al Sistema

1. Ingrese a la dirección web configurada (ej. `http://inventario.intalnet.com`).
2. Inicie sesión con sus credenciales de administrador.
   - **Usuario por defecto**: `admin` (o el configurado en la base de datos).
   - **Contraseña**: (La definida en la instalación).

---

## ⚙️ Paso 2: Configuración Inicial (Catálogos)

Antes de registrar equipos o empleados, es necesario alimentar los catálogos base del sistema para asegurar la consistencia de los datos.

### 2.1. Estructura Organizacional
Diríjase al menú de **Configuración** o **Gestión** para definir:
1. **Sucursales**: Las sedes físicas de la empresa.
2. **Áreas**: Departamentos (ej. Sistemas, RRHH, Compras).
3. **Cargos**: Puestos laborales (ej. Desarrollador, Analista, Gerente).

### 2.2. Catálogos de Hardware
Defina las características de los equipos que administrará:
1. **Tipos de Equipo**: Categorías generales (ej. Laptop, Monitor, Teclado, Mouse).
2. **Marcas**: Fabricantes (ej. Dell, HP, Lenovo).
3. **Modelos**: Referencias específicas asociadas a una marca (ej. Latitude 5420).

> **Nota**: Es vital crear esto en orden, ya que para crear un Modelo necesitará la Marca, y para crear un Equipo necesitará el Modelo.

---

## 👥 Paso 3: Registro de Empleados

Para asignar un equipo, primero debe existir el responsable.

1. Vaya al módulo de **Empleados**.
2. Haga clic en **Agregar Empleado**.
3. Complete los datos personales:
   - Nombre y Apellidos.
   - Documento de identidad.
   - Correo corporativo.
4. Seleccione la **Sucursal**, **Área** y **Cargo** (previamente configurados).
5. Guarde el registro. El empleado aparecerá como "Activo".

---

## 💻 Paso 4: Ingreso de Equipos (Stock)

Registre los activos que entran a la empresa.

1. Vaya al módulo de **Equipos**.
2. Haga clic en **Agregar Equipo**.
3. Complete la ficha técnica:
   - **Serial / Service Tag**: Identificador único del fabricante.
   - **Placa de Inventario**: Código interno de la empresa.
   - **Tipo, Marca y Modelo**.
   - **Estado Inicial**: Seleccione "Disponible".
   - **Características**: Procesador, RAM, Disco (si aplica).
4. Guarde. El equipo quedará en estado **Disponible** en el inventario.

---

## 🤝 Paso 5: Ciclo de Vida - Asignación

Este es el proceso de entregar un equipo a un empleado.

1. Vaya al menú de **Asignaciones** o busque al empleado en la lista.
2. Seleccione la opción **Asignar Equipo**.
3. El sistema le mostrará solo los equipos que están en estado **Disponible**.
4. Seleccione el equipo(s) a entregar (Laptops, Mouse, Cargadores, etc.).
5. **Observaciones**: Anote el estado físico o accesorios extra.
6. **Confirmar Asignación**.
7. **Generar Acta**: El sistema generará un PDF (Acta de Entrega) con los detalles y espacios para firma. **Es obligatorio descargar este archivo.**

---

## ↩️ Paso 6: Devoluciones (Retorno a Stock)

Cuando un empleado se retira o cambia de equipo.

1. Busque al empleado o el equipo asignado.
2. Seleccione la opción **Devolver** o **Recibir Equipo**.
3. Verifique el estado del equipo.
4. Confirme la devolución.
5. **Generar Acta de Devolución**: Documento que certifica que el empleado entregó el activo.
6. El equipo cambia automáticamente su estado a **Disponible** (listo para asignarse a otro) o puede marcarse para **Revisión**.

---

## 🛠️ Paso 7: Mantenimiento y Reparaciones

Si un equipo falla o necesita mantenimiento.

1. Ubique el equipo (debe estar devuelto o puede enviarse directo desde asignación si el flujo lo permite).
2. Seleccione **Enviar a Reparación / Soporte**.
3. Indique el proveedor o técnico y el motivo de la falla.
4. El equipo cambia a estado **En Reparación** (no disponible para asignar).
5. **Retorno**: Cuando el equipo vuelva, registre el reingreso indicando si fue "Reparado" (vuelve a Disponible) o si es irreparable (pasa a Baja).

---

## 📉 Paso 8: Bajas (Retiro Definitivo)

Para equipos obsoletos, robados o dañados irreparablemente.

1. Ubique el equipo en el inventario.
2. Seleccione la opción **Dar de Baja**.
3. Ingrese el motivo (Obsolescencia, Hurto, Daño Total).
4. Adjunte documentos de soporte si es necesario (denuncia policial, informe técnico).
5. Confirme. El equipo saldrá del stock activo y pasará al historial de **Bajas**.

---

## 🛡️ Administración del Sistema

### Gestión de Usuarios
En el módulo de **Usuarios**, puede crear cuentas para otros administradores o técnicos de soporte, asignando roles y permisos específicos.

### Copias de Seguridad (Backups)
El sistema cuenta con un módulo de **Respaldo & Restauración**.
- **Generar Backup**: Crea un archivo SQL con toda la base de datos. Se recomienda hacerlo semanalmente.
- **Restaurar**: Permite recuperar el sistema a un punto anterior en caso de emergencia.

---
**Soporte Técnico**: Para dudas adicionales, contacte al administrador del servidor.
