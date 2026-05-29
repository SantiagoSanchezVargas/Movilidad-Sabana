# 🚀 MovSabana - Sistema de Gestión de Movilidad Urbana

**MovSabana** es una plataforma web diseñada para optimizar la gestión de rutas de transporte en la región de Sabana de Bogotá.

## ✨ Características

- 🔐 Autenticación con roles (Admin, Conductor, Pasajero)
- 📍 CRUD completo de rutas con coordenadas GPS
- 🗺️ Mapas interactivos con Leaflet.js
- 📊 Dashboards especializados por rol
- 🛑 Sistema de paradas intermedias
- 💬 Chat en vivo con JivoChat
- 📝 Auditoría de todas las acciones

## 🛠️ Stack Tecnológico

- **Backend:** PHP 8.2 + Laravel 11
- **Frontend:** Blade + Tailwind CSS + Alpine.js
- **BD Local:** PostgreSQL 14+
- **BD Producción:** MySQL (Railway)
- **Mapas:** Leaflet.js 1.9.4
- **Hosting:** Railway.app

## 📦 Instalación Rápida

```bash
git clone https://github.com/SantiagoSanchezVargas/Movilidad-Sabana.git
cd Movilidad-Sabana
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Accede en: `http://localhost:8000`

## 🧪 Cuentas de Prueba

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Admin | admin@test.com | password |
| Conductor | conductor@test.com | password |
| Pasajero | pasajero@test.com | password |

## 🌐 Deployment

Desplegado en Railway: `movilidad-sabana-production.up.railway.app`

## 📞 Contacto
**Autor:** Santiago Sánchez Vargas  
**Universidad:** Universidad de Cundinamarca  

---

**Made with ❤️ - Mayo 2026**
