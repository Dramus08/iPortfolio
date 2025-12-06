# Git Synchronization Scripts

Ce dossier contient des scripts pour synchroniser facilement votre dépôt Git local avec GitHub. Deux versions sont disponibles : PowerShell (Windows) et Bash (Git Bash, WSL, macOS/Linux).

## Fichiers

- **`sync.ps1`** — Script PowerShell pour Windows PowerShell 5.1+
- **`sync.sh`** — Script Bash pour Git Bash, WSL, ou Linux/macOS

## Actions disponibles

### État et configuration
- **`status`** — Affiche le statut Git court (`git status -sb`)
- **`configure-user`** — Configure l'email et le nom d'utilisateur local
- **`add-gitignore`** — Crée un `.gitignore` standard (écrase le fichier existant)
- **`show-remote`** — Affiche les URL distantes (origin)
- **`list-ignored`** — Liste les fichiers non suivis ignorés et les fichiers suivis correspondant à `.gitignore`

### Gestion des dépendances
- **`untrack`** — Désindexe (retire du dépôt) les dossiers courants : `vendor`, `config`, `public/logs`, `logs`, `uploads`
  - Les fichiers resteront localement mais seront supprimés du dépôt distant lors du prochain push

### Authentification
- **`set-https`** — Configure le gestionnaire d'identifiants pour HTTPS
  - Préalable : Créez un Personal Access Token (PAT) sur GitHub (Settings > Developer settings > Personal access tokens)
  - Scopes requis : `repo` (accès lecture/écriture aux dépôts privés et publics)
  
- **`set-ssh`** — Génère une clé SSH ED25519 et affiche la clé publique
  - Copier la clé publique dans GitHub (Settings > SSH and GPG keys)
  - Après, configurer manuellement le remote en SSH : `git remote set-url origin git@github.com:<owner>/<repo>.git`

### Pull et Push
- **`pull`** — Tire et rebase depuis la branche distante par défaut (détectée automatiquement : main, master, ou autre)
- **`push`** — Pousse la branche locale courante vers origin

## Utilisation

### PowerShell (Windows)

```powershell
Set-Location -LiteralPath 'C:\xampp\htdocs\iPortfolio'

# Voir l'aide
.\scripts\sync.ps1 -Action help

# Voir le statut
.\scripts\sync.ps1 -Action status

# Configurer l'email et nom local
.\scripts\sync.ps1 -Action configure-user -Email 'user@example.com' -Name 'Your Name'

# Créer un .gitignore standard
.\scripts\sync.ps1 -Action add-gitignore

# Désindexer les dossiers ignorés
.\scripts\sync.ps1 -Action untrack

# Configurer HTTPS
.\scripts\sync.ps1 -Action set-https

# Configurer SSH
.\scripts\sync.ps1 -Action set-ssh -Email 'user@example.com'

# Tirer depuis le remote
.\scripts\sync.ps1 -Action pull

# Pousser vers le remote
.\scripts\sync.ps1 -Action push

# Lister les fichiers ignorés
.\scripts\sync.ps1 -Action list-ignored
```

### Bash (Git Bash, WSL, Linux, macOS)

```bash
cd /c/xampp/htdocs/iPortfolio
# ou sur WSL/Linux : cd ~/xampp/htdocs/iPortfolio

# Rendre le script exécutable (une seule fois)
chmod +x scripts/sync.sh

# Voir l'aide
./scripts/sync.sh help

# Voir le statut
./scripts/sync.sh status

# Configurer l'email et nom local
GIT_EMAIL='user@example.com' GIT_NAME='Your Name' ./scripts/sync.sh configure-user

# Créer un .gitignore standard
./scripts/sync.sh add-gitignore

# Désindexer les dossiers ignorés
./scripts/sync.sh untrack

# Configurer HTTPS
./scripts/sync.sh set-https

# Configurer SSH
GIT_EMAIL='user@example.com' ./scripts/sync.sh set-ssh

# Tirer depuis le remote
./scripts/sync.sh pull

# Pousser vers le remote
./scripts/sync.sh push

# Lister les fichiers ignorés
./scripts/sync.sh list-ignored
```

## Flux de synchronisation typique

### Première utilisation (configuration HTTPS)

1. Créer un PAT sur GitHub :
   - Aller à GitHub > Settings > Developer settings > Personal access tokens > Generate new token (classic)
   - Sélectionner le scope `repo`
   - Copier le token (vous ne pourrez le voir qu'une fois)

2. Configurer le script :
   ```powershell
   # PowerShell
   .\scripts\sync.ps1 -Action configure-user -Email 'inouaismail@gmail.com' -Name 'Your Name'
   .\scripts\sync.ps1 -Action set-https
   ```

3. Préparer les fichiers à ignorer :
   ```powershell
   .\scripts\sync.ps1 -Action add-gitignore
   .\scripts\sync.ps1 -Action untrack
   ```

4. Pousser les changements :
   ```powershell
   .\scripts\sync.ps1 -Action pull    # Tirer d'abord
   .\scripts\sync.ps1 -Action push    # Pousser
   # Git demandera votre username et PAT
   ```

### Ou avec SSH (plus sûr à long terme)

1. Générer la clé SSH :
   ```powershell
   .\scripts\sync.ps1 -Action set-ssh -Email 'inouaismail@gmail.com'
   ```

2. Copier la clé publique affichée dans GitHub (Settings > SSH and GPG keys > New SSH key)

3. Changer le remote en SSH :
   ```powershell
   git remote set-url origin git@github.com:Dramus08/iPortfolio.git
   ```

4. Préparer et pousser :
   ```powershell
   .\scripts\sync.ps1 -Action add-gitignore
   .\scripts\sync.ps1 -Action untrack
   .\scripts\sync.ps1 -Action pull
   .\scripts\sync.ps1 -Action push
   ```

## Notes importantes

- **Untrack** fait `git rm --cached` — les fichiers restent localement mais seront supprimés du dépôt distant après un push.
- **Authentification** : Les scripts ne stockent pas vos identifiants. Windows Credential Manager (HTTPS) ou SSH (clé privée protégée) gèrent la sécurité.
- **Erreur 403** : Si vous recevez une erreur 403, vérifiez :
  - Que votre PAT est valide et n'a pas expiré
  - Que vous utiliser le bon compte GitHub
  - Effacez les credentials erronés du Gestionnaire d'identifiants Windows
- **Branche distante** : Les scripts détectent automatiquement la branche distante par défaut (`main`, `master`, ou autre). Si elle est introuvable, créez-la manuellement ou vérifiez le remote.

## Dépannage

### "fatal: couldn't find remote ref main"
- La branche distante n'est peut-être pas `main`. Vérifiez : `git ls-remote --heads origin`
- Ou changez le remote et essayez à nouveau : `git remote show origin`

### "Permission denied (403)"
- Votre token/clé n'a pas la permission ou est expiré.
- Regénérez un PAT ou une clé SSH et mettez à jour GitHub.

### "pathspec 'vendor' did not match any files"
- Le chemin n'est pas suivi (pas dans l'index). Continuez — pas besoin de le désindexer.

## Aide

Pour plus d'aide directement depuis le script :
```powershell
.\scripts\sync.ps1 -Action help
```
ou
```bash
./scripts/sync.sh help
```
