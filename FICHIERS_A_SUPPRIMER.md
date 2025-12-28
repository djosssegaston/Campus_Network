# 🗑️ FICHIERS REACT À SUPPRIMER

## Fichiers JSX/React à supprimer

```
resources/js/
├── app.jsx ❌ SUPPRIMER (remplacé par app.js)
├── bootstrap.js ❌ SUPPRIMER (ancien bootstrap)
├── Components/ ❌ SUPPRIMER TOUT
│   ├── ApplicationLogo.jsx
│   ├── Checkbox.jsx
│   ├── DangerButton.jsx
│   ├── Dropdown.jsx
│   ├── InputError.jsx
│   ├── InputLabel.jsx
│   ├── Modal.jsx
│   ├── NavLink.jsx
│   ├── PrimaryButton.jsx
│   ├── PublicationCard.jsx
│   ├── ResponsiveNavLink.jsx
│   ├── SecondaryButton.jsx
│   └── TextInput.jsx
├── Layouts/ ❌ SUPPRIMER TOUT
│   ├── AppLayout.jsx
│   ├── AuthenticatedLayout.jsx
│   └── GuestLayout.jsx
└── Pages/ ❌ SUPPRIMER TOUT
    ├── Admin.jsx
    ├── Dashboard.jsx
    ├── Feed.jsx
    ├── Messages.jsx
    ├── PublicationCreate.jsx
    ├── Welcome.jsx
    ├── Auth/
    │   ├── ConfirmPassword.jsx
    │   ├── ForgotPassword.jsx
    │   ├── Login.jsx
    │   ├── Register.jsx
    │   ├── ResetPassword.jsx
    │   └── VerifyEmail.jsx
    ├── Groupes/
    │   ├── Create.jsx
    │   ├── Index.jsx
    │   └── Show.jsx
    └── Profile/
        ├── Edit.jsx
        └── Partials/
            ├── DeleteUserForm.jsx
            ├── UpdatePasswordForm.jsx
            └── UpdateProfileInformationForm.jsx
```

## ⚡ Instructions de Nettoyage

### Via Terminal/PowerShell:
```powershell
# Supprimer les composants React
Remove-Item -Path "resources/js/Components" -Recurse -Force

# Supprimer les layouts React
Remove-Item -Path "resources/js/Layouts" -Recurse -Force

# Supprimer les pages React
Remove-Item -Path "resources/js/Pages" -Recurse -Force

# Supprimer les fichiers config React
Remove-Item -Path "resources/js/app.jsx" -Force
Remove-Item -Path "resources/js/bootstrap.js" -Force
```

### Via VS Code:
1. Ouvrir le Explorer (Ctrl+Shift+E)
2. Naviguer vers `resources/js/`
3. Supprimer les dossiers:
   - `Components/`
   - `Layouts/`
   - `Pages/`
4. Supprimer les fichiers:
   - `app.jsx`
   - `bootstrap.js`

## ✅ Vérification après suppression

Votre dossier `resources/js/` devrait ressembler à:
```
resources/js/
└── app.js ✅ GARDER
```

## 📌 Notes Importantes

- ✅ Le code Blade est **fonctionnel** sans ces fichiers
- ✅ Alpine.js gère l'interactivité légère
- ✅ Axios gère les appels API
- ✅ Tailwind CSS stylise tout
- ✅ Pas besoin de bundler React

## 🔄 Migration Complète

Avant suppression:
```bash
npm install
npm run build  # Compiler les assets Tailwind/Vite
```

Après suppression:
```bash
npm run dev    # Développement avec watch mode
# ou
npm run build  # Production build
```

---

**Suppression des fichiers React = Migration terminée ✅**
