# 📐 Organización del proyecto (por feature)

En este proyecto trabajaremos con una **estructura por feature**, con el objetivo de mantener el código ordenado, claro y fácil de mantener.

La lógica principal del sistema estará concentrada en **UseCases**, evitando Controllers grandes y difíciles de entender.

---

## 📁 Estructura general

```text
app/
 └── Application/
      └── UseCases/
           ├── Inventory/
           │    ├── RegisterItemUseCase.php
           │    └── DTO/
           │         └── RegisterItemDTO.php
           │
           ├── Loans/
           │    ├── CreateLoanUseCase.php
           │    └── DTO/
           │         └── CreateLoanDTO.php
           │
           ├── Reservations/
           │    ├── CreateReservationUseCase.php
           │    └── DTO/
           │         └── CreateReservationDTO.php
           │
           └── Users/
                ├── CreateUserUseCase.php
                └── DTO/
                     └── CreateUserDTO.php
```

---

## 🧩 ¿Qué es un Feature?

Un **feature** representa un módulo funcional del sistema, por ejemplo:
- Inventario
- Préstamos
- Reservas
- Usuarios

Cada feature agrupa todo lo necesario para ejecutar sus acciones.

---

## ⚙️ UseCases

Los **UseCases** representan acciones del sistema (una clase por acción).

Ejemplos:
- `RegisterItemUseCase`
- `CreateLoanUseCase`
- `ReturnLoanUseCase`

### Responsabilidades
- Contener la **lógica de negocio**
- Orquestar el flujo de la acción
- Usar directamente los **Models (Eloquent)**

### No deben
- Usar `Request` o `Response`
- Contener lógica de validación HTTP

---

## 📦 DTOs (Data Transfer Objects)

Los **DTOs** se utilizan para transportar datos desde el Controller hacia el UseCase.

### Responsabilidades
- Contener datos ya validados
- Evitar pasar el `Request` directamente al UseCase

### No deben
- Tener lógica de negocio
- Acceder a la base de datos

---

## 🌐 Controllers

Los Controllers se encargan de:
- Recibir la petición HTTP
- Validar datos (FormRequest)
- Construir el DTO
- Ejecutar el UseCase
- Devolver la respuesta HTTP

---

## 🔁 Flujo general

```
Controller
   ↓
DTO
   ↓
UseCase
   ↓
Model (Eloquent)
```

---

## ✅ Beneficios de esta organización

- Código más limpio y legible
- Controllers delgados
- Lógica centralizada
- Fácil mantenimiento
- Escalabilidad por módulos (features)

---

## 📝 Nota final

No se está utilizando una arquitectura compleja (DDD, Repositories, etc.).  
El objetivo principal es **orden y claridad**, evitando sobre-ingeniería.
