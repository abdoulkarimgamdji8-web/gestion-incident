# Configuration SPA avec Authentification par Rôles

## Vue d'ensemble

L'application est configurée comme une **Single Page Application (SPA)** avec authentification basée sur les tokens Sanctum et gestion des rôles multi-utilisateurs.

## Architecture

### Authentification
- **Guard**: `sanctum` (API tokens)
- **Provider**: `users` (Eloquent User model)
- **Stateful**: Configuré pour les requêtes depuis le front-end SPA

### Rôles disponibles
- `Admin` - Accès complet
- `Directeur Technicien` - Gestion des techniciens
- `Responsable DT` - Responsable domaine technique
- `Technicien` - Intervention sur incidents
- `Prestataire Externe` - Prestataire externe
- `Agent Station` - Agent de station

---

## Endpoints API

### Authentification (Public)

#### Login
```bash
POST /api/login
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "password",
  "remember": false
}

Response 200:
{
  "message": "Connexion réussie",
  "token": "1|abc...",
  "user": {
    "id": 1,
    "nom": "Test",
    "prenom": "User",
    "email": "test@example.com",
    "role_id": 1
  },
  "role": "Admin"
}
```

### Authentification (Protégé - Requête avec Token)

Tous les endpoints ci-dessous nécessitent l'header:
```
Authorization: Bearer <token>
```

#### Récupérer utilisateur courant
```bash
GET /api/me

Response 200:
{
  "user": {
    "id": 1,
    "nom": "Test",
    "prenom": "User",
    "email": "test@example.com",
    "numero": 123456,
    "statut": true,
    "role_id": 1
  },
  "role": "Admin"
}
```

#### Vérifier le rôle
```bash
POST /api/check-role
Content-Type: application/json

{
  "roles": ["Admin", "Technicien"]
}

Response 200:
{
  "has_role": true,
  "current_role": "Admin"
}
```

#### Logout courant
```bash
POST /api/logout

Response 200:
{
  "message": "Déconnexion réussie"
}
```

#### Logout partout (révoquer tous les tokens)
```bash
POST /api/logout-all

Response 200:
{
  "message": "Déconnecté de tous les appareils"
}
```

### Dashboard

#### Dashboard général (Tous les utilisateurs authentifiés)
```bash
GET /api/dashboard

Response 200:
{
  "message": "Bienvenue sur le dashboard",
  "user": { ... },
  "role": "Admin"
}
```

#### Dashboard Admin (Admin seulement)
```bash
GET /api/admin/dashboard

Response 200:
{
  "message": "Panneau d'administration",
  "data": {}
}

Response 403 (Accès refusé):
{
  "message": "Accès non autorisé. Rôle requis : Admin",
  "current_role": "Technicien"
}
```

#### Dashboard Technicien (Technicien, Responsable DT, Directeur Technicien)
```bash
GET /api/technician/dashboard

Response 200:
{
  "message": "Panneau technicien",
  "data": {}
}
```

---

## Exemples d'utilisation Front-end

### 1. Login (Vue/React)
```javascript
// axios/fetch login
const response = await fetch('/api/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    email: 'test@example.com',
    password: 'password'
  })
});

const data = await response.json();
localStorage.setItem('token', data.token);
localStorage.setItem('user', JSON.stringify(data.user));
localStorage.setItem('role', data.role);
```

### 2. Requête authentifiée
```javascript
const headers = {
  'Authorization': `Bearer ${localStorage.getItem('token')}`,
  'Content-Type': 'application/json'
};

const response = await fetch('/api/dashboard', { headers });
```

### 3. Logout
```javascript
const token = localStorage.getItem('token');
await fetch('/api/logout', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
  }
});

localStorage.removeItem('token');
localStorage.removeItem('user');
localStorage.removeItem('role');
```

### 4. Vérifier le rôle avant d'afficher
```javascript
const role = localStorage.getItem('role');
if (['Admin', 'Directeur Technicien'].includes(role)) {
  // Afficher menu admin
}
```

---

## Gestion des Rôles dans les Routes

### Middleware `role:`
Le middleware `role:` accepte plusieurs rôles séparés par des virgules:

```php
// Route accessible par Admin OU Directeur Technicien
Route::middleware('role:Admin,Directeur Technicien')->group(function () {
    Route::get('/special', ...);
});

// Route accessible par tous les techniciens
Route::middleware('role:Technicien,Responsable DT,Directeur Technicien')->group(function () {
    Route::get('/incidents', ...);
});
```

---

## Modifications à effectuer

### 1. Configurer CORS (si front-end sur domaine différent)
```php
// config/cors.php
'allowed_origins' => ['http://localhost:3000', 'https://app.example.com'],
```

### 2. Configurer Sanctum
```php
// .env
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000,app.example.com
```

### 3. Ajouter relation Role dans User (déjà fait)
```php
public function role()
{
    return $this->belongsTo(Role::class);
}
```

---

## Erreurs courantes

### 401 - Token invalide/expiré
```json
{
  "message": "Unauthenticated."
}
```
**Solution**: Renvoyer l'utilisateur vers le login

### 403 - Accès refusé
```json
{
  "message": "Accès non autorisé. Rôle requis : Admin",
  "current_role": "Technicien"
}
```
**Solution**: L'utilisateur n'a pas le rôle requis

### 422 - Validation échouée
```json
{
  "message": "Unprocessable Content",
  "errors": { "email": ["..."] }
}
```
**Solution**: Vérifier les données envoyées

---

## Testing

Teste les endpoints avec curl:

```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'

# Get me
TOKEN="<token_reçu>"
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer $TOKEN"

# Logout
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer $TOKEN"
```

---

## Prochaines étapes

1. ✅ AuthController avec login/logout/me
2. ✅ Middleware CheckRole pour vérification des rôles
3. ✅ Routes API avec protection par rôles
4. ⏳ Créer endpoints pour gérer les ressources (incidents, utilisateurs, etc.)
5. ⏳ Ajouter contrôleurs pour API (UserController, IncidentController, etc.)
6. ⏳ Configurer CORS pour production
7. ⏳ Ajouter refresh token logic (optional)
