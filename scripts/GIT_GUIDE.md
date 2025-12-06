# Guide Complet des Commandes Git

## Table des matières
1. [Configuration](#configuration)
2. [Initialisation et Clonage](#initialisation-et-clonage)
3. [Inspection et Statut](#inspection-et-statut)
4. [Stage et Commit](#stage-et-commit)
5. [Branches](#branches)
6. [Fusion et Rebase](#fusion-et-rebase)
7. [Synchronisation Distante](#synchronisation-distante)
8. [Historique et Recherche](#historique-et-recherche)
9. [Annulation et Correction](#annulation-et-correction)
10. [Étiquettes](#étiquettes)
11. [Stash](#stash)

---

## Configuration

### `git config`
**Utilité** : Configure les paramètres Git (nom, email, etc.)

```bash
# Configuration globale (tous les projets)
git config --global user.name "Votre Nom"
git config --global user.email "votre.email@example.com"

# Configuration locale (ce projet seulement)
git config --local user.name "Autre Nom"
git config --local user.email "autre.email@example.com"

# Voir toutes les configurations
git config --list

# Voir la config locale
git config --local --list
```

**Cas d'usage** :
- Personnaliser l'auteur des commits
- Configurer l'éditeur par défaut (`core.editor`)
- Ajouter des alias pour raccourcir les commandes

---

## Initialisation et Clonage

### `git init`
**Utilité** : Crée un nouveau dépôt Git dans le dossier courant

```bash
# Créer un nouveau projet
mkdir mon-projet
cd mon-projet
git init

# Voir les fichiers cachés (dépôt .git créé)
ls -la
```

**Cas d'usage** : Initialiser un projet local avant la première synchronisation avec GitHub

---

### `git clone`
**Utilité** : Télécharge un dépôt distant et crée une copie locale

```bash
# Cloner via HTTPS
git clone https://github.com/utilisateur/projet.git

# Cloner via SSH
git clone git@github.com:utilisateur/projet.git

# Cloner dans un dossier spécifique
git clone https://github.com/utilisateur/projet.git mon-dossier
```

**Cas d'usage** : Récupérer un projet existant depuis GitHub

---

## Inspection et Statut

### `git status`
**Utilité** : Affiche l'état du dépôt (fichiers modifiés, non suivis, etc.)

```bash
# État complet
git status

# État court (plus lisible)
git status -s
git status -sb  # avec la branche

# État avec les chemins ignorés
git status --ignored
```

**Sortie typique** :
```
## main
 M fichier_modifié.txt       (modifié mais non stagna)
?? fichier_nouveau.txt       (non suivi)
A  fichier_stagé.txt         (stagé pour commit)
```

**Cas d'usage** : Vérifier ce qui a changé avant de committer

---

### `git diff`
**Utilité** : Affiche les différences entre fichiers

```bash
# Différences non stagées
git diff

# Différences stagées
git diff --staged
git diff --cached

# Différences avec un commit spécifique
git diff HEAD

# Différences entre deux commits
git diff commit1 commit2

# Différences pour un fichier spécifique
git diff fichier.txt

# Stat (résumé des changements)
git diff --stat
```

**Cas d'usage** : Revoir vos changements avant de committer

---

### `git ls-files`
**Utilité** : Liste les fichiers suivis par Git

```bash
# Tous les fichiers suivis
git ls-files

# Fichiers ignorés (non suivis)
git ls-files -i --exclude-standard --others

# Fichiers stagés et non stagés
git ls-files --stage
```

**Cas d'usage** : Vérifier quels fichiers Git suit

---

## Stage et Commit

### `git add`
**Utilité** : Ajoute des fichiers à la zone de staging (index)

```bash
# Ajouter un fichier spécifique
git add fichier.txt

# Ajouter plusieurs fichiers
git add fichier1.txt fichier2.txt

# Ajouter tous les fichiers modifiés
git add .
git add -A

# Ajouter interactivement (choisir les changements)
git add -p

# Ajouter par pattern
git add *.js  # tous les .js
```

**Cas d'usage** : Préparer les fichiers à committer

---

### `git commit`
**Utilité** : Sauvegarde les changements stagés avec un message

```bash
# Committer avec un message court
git commit -m "Ajouter la fonctionnalité X"

# Committer avec message multilignes
git commit -m "Titre court" -m "Description détaillée..."

# Committer tout (fichiers modifiés uniquement, pas les nouveaux)
git commit -a -m "Message"

# Amender (modifier) le dernier commit
git commit --amend -m "Nouveau message"
git commit --amend --no-edit  # sans changer le message

# Committer sans message (ouvre l'éditeur)
git commit
```

**Bonnes pratiques** :
- Messages clairs et concis en impératif ("Ajouter" pas "Ajouté")
- Première ligne ≤ 50 caractères
- Lignes suivantes expliquent le *pourquoi*, pas le *quoi*

**Cas d'usage** : Sauvegarder les changements avec historique

---

## Branches

### `git branch`
**Utilité** : Crée, liste et gère les branches

```bash
# Lister toutes les branches locales
git branch

# Lister les branches distantes
git branch -r

# Lister toutes les branches (locales + distantes)
git branch -a

# Créer une nouvelle branche
git branch nom-branche

# Créer et basculer vers la nouvelle branche
git branch -c nom-branche  # ou git switch -c

# Supprimer une branche
git branch -d nom-branche
git branch -D nom-branche  # forcer la suppression

# Renommer une branche
git branch -m ancien-nom nouveau-nom
```

**Cas d'usage** : Organiser le travail en parallèle (features, fixes, etc.)

---

### `git switch` / `git checkout`
**Utilité** : Change de branche ou restaure des fichiers

```bash
# Basculer vers une branche existante
git switch nom-branche
git checkout nom-branche  # commande ancienne

# Créer et basculer vers une nouvelle branche
git switch -c nom-branche
git checkout -b nom-branche  # ancienne syntaxe

# Revenir à la branche précédente
git switch -

# Restaurer un fichier à son état du dernier commit
git restore fichier.txt
git checkout -- fichier.txt  # ancienne syntaxe

# Restaurer un fichier d'un commit spécifique
git restore --source=commit-hash fichier.txt
```

**Cas d'usage** : Travailler sur différentes branches sans conflits

---

## Fusion et Rebase

### `git merge`
**Utilité** : Fusionne une branche dans la branche courante

```bash
# Fusionner une branche
git merge nom-branche

# Fusionner sans créer de commit de fusion (fast-forward)
git merge --ff-only nom-branche

# Fusionner avec un commit de fusion même si fast-forward possible
git merge --no-ff nom-branche -m "Message de fusion"

# Fusionner et créer un commit spécifique
git merge --squash nom-branche
git commit -m "Message final"

# Annuler une fusion en cours
git merge --abort
```

**Cas d'usage** : Intégrer les changements d'une branche dans une autre

---

### `git rebase`
**Utilité** : Réapplique les commits d'une branche sur une autre base

```bash
# Rebaser la branche courante sur main
git rebase main

# Rebase interactif (modifier/réordonner commits)
git rebase -i HEAD~3  # 3 derniers commits
git rebase -i main

# Continuer après un conflit
git rebase --continue

# Annuler un rebase
git rebase --abort
```

**Différence Merge vs Rebase** :
- `merge` : Crée un historique avec branches visibles
- `rebase` : Linéarise l'historique (plus propre)

**Cas d'usage** : Nettoyer l'historique avant de pousser

---

## Synchronisation Distante

### `git remote`
**Utilité** : Gère les dépôts distants

```bash
# Lister les remotes
git remote
git remote -v  # avec les URLs

# Ajouter un remote
git remote add origin https://github.com/user/repo.git

# Changer l'URL d'un remote
git remote set-url origin nouvelle-url

# Supprimer un remote
git remote remove origin

# Afficher les détails d'un remote
git remote show origin
```

**Cas d'usage** : Configurer la synchronisation avec GitHub

---

### `git fetch`
**Utilité** : Télécharge les changements du remote sans les appliquer

```bash
# Télécharger toutes les branches
git fetch

# Télécharger d'un remote spécifique
git fetch origin

# Télécharger avec suppression des branches distantes supprimées
git fetch --prune

# Voir ce qui sera téléchargé
git fetch --dry-run
```

**Cas d'usage** : Vérifier les changements distants avant d'intégrer

---

### `git pull`
**Utilité** : Télécharge et intègre les changements distants

```bash
# Pull simple (équivalent à fetch + merge)
git pull

# Pull avec rebase (fetch + rebase)
git pull --rebase

# Pull sans auto-merge (fail si conflit)
git pull --no-ff

# Pull depuis une branche spécifique
git pull origin main
```

**Cas d'usage** : Mettre à jour votre branche locale

---

### `git push`
**Utilité** : Envoie vos commits au remote

```bash
# Pousser la branche courante
git push

# Pousser vers un remote spécifique
git push origin main

# Pousser et configurer le tracking
git push -u origin main

# Pousser toutes les branches
git push origin --all

# Pousser avec suppression des branches
git push origin --delete nom-branche

# Force push (attention : réécrit l'historique)
git push --force-with-lease origin main

# Pousser les tags
git push origin --tags
```

**Cas d'usage** : Partager vos changements sur GitHub

---

## Historique et Recherche

### `git log`
**Utilité** : Affiche l'historique des commits

```bash
# Historique complet
git log

# Une ligne par commit
git log --oneline

# Graphique avec branches
git log --oneline --graph --all

# Historique d'un fichier spécifique
git log fichier.txt

# Historique avec les changements (diff)
git log -p

# Commits depuis une date
git log --since="2 weeks ago"
git log --until="2024-01-01"

# Commits par auteur
git log --author="Nom Auteur"

# Commits avec keyword
git log --grep="keyword"

# Limit le nombre de commits
git log -n 10  # 10 derniers commits
```

**Cas d'usage** : Comprendre l'historique du projet

---

### `git show`
**Utilité** : Affiche les détails d'un commit

```bash
# Détails du dernier commit
git show

# Détails d'un commit spécifique
git show commit-hash

# Détails d'un fichier dans un commit
git show commit-hash:fichier.txt

# Sans les changements (juste le message)
git show --stat commit-hash
```

**Cas d'usage** : Inspecter un commit spécifique

---

### `git blame`
**Utilité** : Affiche qui a modifié chaque ligne

```bash
# Blame complet
git blame fichier.txt

# Blame avec des détails supplémentaires
git blame -v fichier.txt

# Blame pour une plage de lignes
git blame -L 10,20 fichier.txt
```

**Cas d'usage** : Trouver qui a fait un changement spécifique

---

## Annulation et Correction

### `git revert`
**Utilité** : Crée un nouveau commit qui annule un ancien commit

```bash
# Annuler un commit spécifique
git revert commit-hash

# Annuler le dernier commit
git revert HEAD

# Annuler sans créer de commit
git revert -n commit-hash
```

**Cas d'usage** : Annuler un changement sans récrire l'historique

---

### `git reset`
**Utilité** : Déplace la branche et réinitialise l'index

```bash
# Déstager tous les fichiers
git reset

# Déstager un fichier spécifique
git reset fichier.txt

# Revenir à un commit (soft : garde les changements)
git reset --soft HEAD~1

# Revenir à un commit (mixed : déstage les changements)
git reset --mixed HEAD~1

# Revenir à un commit (hard : supprime tout)
git reset --hard HEAD~1

# Revenir à un commit distant
git reset --hard origin/main
```

**Attention** : `--hard` supprime les changements locaux

**Cas d'usage** : Annuler les derniers commits localement

---

### `git clean`
**Utilité** : Supprime les fichiers non suivis

```bash
# Voir ce qui sera supprimé
git clean -n

# Supprimer les fichiers non suivis
git clean -f

# Supprimer aussi les dossiers vides
git clean -fd

# Supprimer aussi les fichiers ignorés
git clean -fdx

# Interactif (choisir ce qui est supprimé)
git clean -i
```

**Cas d'usage** : Nettoyer les fichiers temporaires

---

## Étiquettes

### `git tag`
**Utilité** : Crée des points de repère nommés (versions)

```bash
# Créer une étiquette légère
git tag v1.0.0

# Créer une étiquette annotée (avec message)
git tag -a v1.0.0 -m "Version 1.0.0"

# Lister les étiquettes
git tag
git tag -l "v1.*"

# Afficher les détails d'une étiquette
git show v1.0.0

# Pousser une étiquette spécifique
git push origin v1.0.0

# Pousser toutes les étiquettes
git push origin --tags

# Supprimer une étiquette locale
git tag -d v1.0.0

# Supprimer une étiquette distante
git push origin --delete v1.0.0
```

**Cas d'usage** : Marquer les versions de release

---

## Stash

### `git stash`
**Utilité** : Sauvegarde temporaire des changements sans committer

```bash
# Sauvegarder les changements
git stash

# Avec un message descriptif
git stash save "Description des changements"

# Lister les stash
git stash list

# Appliquer le dernier stash (et le garder)
git stash apply

# Appliquer et supprimer le dernier stash
git stash pop

# Appliquer un stash spécifique
git stash apply stash@{0}

# Supprimer un stash
git stash drop stash@{0}

# Supprimer tous les stash
git stash clear

# Voir les changements d'un stash
git stash show stash@{0}
git stash show -p stash@{0}  # avec diff
```

**Cas d'usage** : Basculer de branche temporairement sans perdre le travail

---

## Workflow Git Typique

### Créer une feature
```bash
git switch -c feature/nouvelle-fonction
# ... faire des changements ...
git add .
git commit -m "Ajouter nouvelle fonction"
```

### Mettre à jour avec main
```bash
git fetch origin
git rebase origin/main
# Ou : git pull --rebase origin main
```

### Pousser et créer une Pull Request
```bash
git push -u origin feature/nouvelle-fonction
# Créer une PR sur GitHub
```

### Fusionner après review
```bash
git switch main
git pull origin main
git merge feature/nouvelle-fonction
git push origin main
```

### Nettoyer
```bash
git branch -d feature/nouvelle-fonction
git push origin --delete feature/nouvelle-fonction
```

---

## Conseils et Bonnes Pratiques

1. **Committer souvent** : Petits commits atomiques sont meilleurs
2. **Messages clairs** : Décrivez le *pourquoi*, pas le *quoi*
3. **Brancher** : Une branche par feature ou bugfix
4. **Pull avant push** : Toujours vérifier les changements distants
5. **Rebase vs Merge** : Rebase pour nettoyer, merge pour partager
6. **Ne pas forcer** : Éviter `git push --force` sur les branches partagées
7. **Tagguer les versions** : Marquer les releases avec des tags

---

## Commandes d'Urgence

```bash
# "J'ai tout cassé, retour au dernier commit"
git reset --hard HEAD

# "J'ai committé quelque chose que je ne voulais pas"
git revert commit-hash

# "J'ai perdu mon commit"
git reflog  # puis git reset --hard commit-hash

# "J'ai un conflit"
git merge --abort  # ou git rebase --abort
```
