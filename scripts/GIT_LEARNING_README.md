# Scripts d'Apprentissage Git

Ce dossier contient des ressources interactives pour vous familiariser avec les commandes Git et leurs cas d'usage.

## Fichiers

### 1. `GIT_GUIDE.md`
**Guide complet des commandes Git** avec explications détaillées

**Contenu:**
- Configuration
- Initialisation et clonage
- Inspection (status, diff, ls-files)
- Stage et Commit
- Branches
- Fusion et Rebase
- Synchronisation distante
- Historique et recherche
- Annulation et correction
- Étiquettes (tags)
- Stash
- Workflow complet

**Usage:**
```bash
# Ouvrir dans votre éditeur favori
code GIT_GUIDE.md
# ou
cat GIT_GUIDE.md | less
```

---

### 2. `git-learn.sh`
**Script interactif Bash** pour pratiquer les commandes Git

**Utilité:** Menu interactif avec 10 catégories pour apprendre et tester les commandes

**Catégories:**
1. Configuration (config, user.email, user.name)
2. Inspection (status, diff, ls-files)
3. Staging & Commits (add, commit, amend)
4. Branches (branch, switch, merge, rebase)
5. Remote (remote, fetch, pull, push)
6. Historique (log, show, blame)
7. Annulation (reset, revert, clean, restore)
8. Stash (sauvegardes temporaires)
9. Tags & Release
10. Workflow complet (scénario pratique)

**Usage:**
```bash
# Rendre exécutable (première fois)
chmod +x scripts/git-learn.sh

# Lancer le script
cd /chemin/vers/iPortfolio
./scripts/git-learn.sh
```

**Exemple d'utilisation interactive:**
```
=== Git Learning Interactive Script ===

Catégories:
  1. Configuration
  2. Inspection
  ...
  0. Quitter

Choisissez une catégorie (0-10): 1

=== Git Configuration ===

Commandes disponibles:
  1. Configurer l'email local
  2. Configurer le nom local
  ...

Choisissez (0-4): 1
Email: votre.email@example.com
Set local user.email to votre.email@example.com
✓ Email configuré: votre.email@example.com
```

---

### 3. `git-learn.ps1`
**Script interactif PowerShell** (équivalent Windows)

**Utilité:** Même menu que `git-learn.sh` mais pour PowerShell

**Usage:**
```powershell
# Vérifier la politique d'exécution
Get-ExecutionPolicy

# Si nécessaire, autoriser les scripts
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

# Lancer le script
cd C:\xampp\htdocs\iPortfolio
.\scripts\git-learn.ps1
```

**Ou (bypass direct):**
```powershell
cd C:\xampp\htdocs\iPortfolio
powershell -ExecutionPolicy Bypass -File .\scripts\git-learn.ps1
```

---

## Structure des Scripts Interactifs

### Menu Principal
Offre 10 catégories d'apprentissage + options spécifiques

### Sous-menus
Chaque catégorie offre 4-6 options :
- Exécuter une commande
- Voir les résultats
- Explorer les options (courtes, complètes, etc.)

### Retour
- Affichage des résultats de la commande
- Possibilité de tester une autre option ou catégorie
- Retour au menu à tout moment (option 0)

---

## Cas d'Usage Typiques

### Pour un débutant Git
1. Lire `GIT_GUIDE.md` sections "Configuration" et "Initialisation"
2. Exécuter `git-learn.sh` catégorie 1 (Configuration)
3. Exécuter `git-learn.sh` catégorie 3 (Staging & Commits)

### Pour pratiquer les branches
1. Lire `GIT_GUIDE.md` section "Branches"
2. Exécuter `git-learn.sh` catégorie 4 (Branches)

### Pour comprendre l'historique
1. Lire `GIT_GUIDE.md` section "Historique et Recherche"
2. Exécuter `git-learn.sh` catégorie 6 (Historique)

### Pour un workflow complet
1. Exécuter `git-learn.sh` catégorie 10 (Workflow Complet)

---

## Commandes Principales Disponibles

| Catégorie | Commandes |
|-----------|-----------|
| **Configuration** | config, user.name, user.email |
| **Inspection** | status, diff, ls-files |
| **Staging** | add, commit, amend |
| **Branches** | branch, switch, merge, rebase |
| **Remote** | remote, fetch, pull, push |
| **Historique** | log, show, blame |
| **Annulation** | reset, revert, restore, clean |
| **Stash** | stash list, apply, pop |
| **Tags** | tag, tag -a |
| **Workflow** | Scénario complet (feature branch) |

---

## Tips et Astuces

### Raccourcis Utiles
```bash
# Créer un alias pour raccourcir les commandes
git config --global alias.co checkout
git config --global alias.br branch
git config --global alias.ci commit
git config --global alias.st status

# Utilisation
git st        # git status
git co main   # git checkout main
git br -a     # git branch -a
```

### Avant de pousser
```bash
# Toujours vérifier l'état
git status

# Voir les changements
git diff

# Voir l'historique local
git log --oneline -5

# Puis pousser
git push
```

### Annuler les erreurs courantes
```bash
# "J'ai commité quelque chose par erreur"
git revert HEAD  # ou git reset --soft HEAD~1

# "J'ai modifié le mauvais fichier"
git restore fichier.txt

# "Tout est cassé"
git reset --hard origin/main
```

---

## Dépannage

### "Le script ne s'exécute pas (PowerShell)"
**Solution:**
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### "Le script ne s'exécute pas (Bash)"
**Solution:**
```bash
chmod +x scripts/git-learn.sh
./scripts/git-learn.sh
```

### "Je suis en mode rebase interactif"
**Solution:**
```bash
# Pour quitter (annuler)
git rebase --abort
# Ou pour finir
git rebase --continue  # après résoudre les conflits
```

### "Les commandes ne font rien"
**Vérifier:**
1. Que vous êtes dans un dépôt Git : `git status`
2. Qu'il y a des changements : `git diff`
3. Que vous êtes sur la bonne branche : `git branch --show-current`

---

## Prochaines Étapes

1. **Lire le guide** : Explorez `GIT_GUIDE.md`
2. **Pratiquer interactivement** : Utilisez `git-learn.sh` ou `git-learn.ps1`
3. **Appliquer au projet** : Utilisez les commandes apprises sur `iPortfolio`
4. **Consulter les autres scripts** : Voir `sync.sh` et `sync.ps1` pour les workflows avancés

---

## Ressources Supplémentaires

- [Pro Git Book](https://git-scm.com/book/en/v2) - Guide officiel complet
- [Atlassian Git Tutorials](https://www.atlassian.com/git/tutorials) - Tutoriels pratiques
- `git --help` - Aide intégrée (exécutez dans un terminal)
- `git [commande] --help` - Aide pour une commande spécifique

---

**Créé pour apprendre et pratiquer Git efficacement!**
