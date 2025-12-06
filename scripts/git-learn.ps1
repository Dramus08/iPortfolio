<#
.SYNOPSIS
  Script interactif pour apprendre les commandes Git (PowerShell)
.DESCRIPTION
  Menu interactif pour explorer et pratiquer les commandes Git courantes
.EXAMPLE
  .\git-learn.ps1
#>

$ErrorActionPreference = "SilentlyContinue"

function Write-Title { Write-Host "=== $($args -join ' ') ===" -ForegroundColor Blue }
function Write-Success { Write-Host "✓ $($args -join ' ')" -ForegroundColor Green }
function Write-Error { Write-Host "✗ $($args -join ' ')" -ForegroundColor Red }
function Write-Info { Write-Host "ℹ $($args -join ' ')" -ForegroundColor Yellow }
function Write-Cmd { Write-Host "$ $($args -join ' ')" -ForegroundColor Magenta }

function Show-MainMenu {
  Clear-Host
  Write-Title "Git Learning Interactive Script"
  Write-Host ""
  Write-Host "Catégories:"
  Write-Host "  1. Configuration (config, user, email)"
  Write-Host "  2. Inspection (status, diff, log)"
  Write-Host "  3. Staging & Commits (add, commit, amend)"
  Write-Host "  4. Branches (branch, switch, merge)"
  Write-Host "  5. Remote (remote, push, pull, fetch)"
  Write-Host "  6. Historique & Recherche (log, show, blame)"
  Write-Host "  7. Annulation (reset, revert, clean)"
  Write-Host "  8. Stash (sauvegardes temporaires)"
  Write-Host "  9. Tags & Release (tag, release)"
  Write-Host " 10. Workflow complet (scénario pratique)"
  Write-Host "  0. Quitter"
  Write-Host ""
  $choice = Read-Host "Choisissez une catégorie (0-10)"
  
  switch ($choice) {
    "1" { Show-Configuration }
    "2" { Show-Inspection }
    "3" { Show-Staging }
    "4" { Show-Branches }
    "5" { Show-Remote }
    "6" { Show-History }
    "7" { Show-Annulation }
    "8" { Show-Stash }
    "9" { Show-Tags }
    "10" { Show-Workflow }
    "0" { Write-Host "Au revoir!"; exit 0 }
    default { Write-Error "Choix invalide"; Start-Sleep 1; Show-MainMenu }
  }
}

function Show-Configuration {
  Write-Title "Git Configuration"
  Write-Host ""
  Write-Host "Commandes disponibles:"
  Write-Host "  1. Configurer l'email local"
  Write-Host "  2. Configurer le nom local"
  Write-Host "  3. Voir la configuration"
  Write-Host "  4. Configurer globalement"
  Write-Host "  0. Retour au menu"
  Write-Host ""
  $choice = Read-Host "Choisissez (0-4)"
  
  switch ($choice) {
    "1" {
      $email = Read-Host "Email"
      Write-Cmd "git config --local user.email '$email'"
      git config --local user.email $email
      Write-Success "Email configuré: $(git config user.email)"
    }
    "2" {
      $name = Read-Host "Nom"
      Write-Cmd "git config --local user.name '$name'"
      git config --local user.name $name
      Write-Success "Nom configuré: $(git config user.name)"
    }
    "3" {
      Write-Cmd "git config --list"
      git config --list | Select-String "^user\.|^core\." | Select-Object -First 20
    }
    "4" {
      $email = Read-Host "Email global"
      Write-Cmd "git config --global user.email '$email'"
      git config --global user.email $email
      Write-Success "Email global configuré"
    }
    "0" { Show-MainMenu }
    default { Write-Error "Choix invalide"; Start-Sleep 1; Show-Configuration }
  }
  Read-Host "Appuyez sur Entrée pour continuer"
  Show-Configuration
}

function Show-Inspection {
  Write-Title "Inspection du dépôt"
  Write-Host ""
  Write-Host "Commandes disponibles:"
  Write-Host "  1. git status (voir l'état)"
  Write-Host "  2. git status -s (compacte)"
  Write-Host "  3. git diff (changements non stagés)"
  Write-Host "  4. git diff --staged (changements stagés)"
  Write-Host "  5. git ls-files (fichiers suivis)"
  Write-Host "  0. Retour au menu"
  Write-Host ""
  $choice = Read-Host "Choisissez (0-5)"
  
  switch ($choice) {
    "1" {
      Write-Cmd "git status"
      git status
    }
    "2" {
      Write-Cmd "git status -s"
      git status -s
    }
    "3" {
      Write-Cmd "git diff"
      git diff | Select-Object -First 50
    }
    "4" {
      Write-Cmd "git diff --staged"
      git diff --staged | Select-Object -First 50
    }
    "5" {
      Write-Cmd "git ls-files | Select -First 20"
      git ls-files | Select-Object -First 20
      Write-Host "... (et plus)"
    }
    "0" { Show-MainMenu }
    default { Write-Error "Choix invalide"; Start-Sleep 1; Show-Inspection }
  }
  Read-Host "Appuyez sur Entrée pour continuer"
  Show-Inspection
}

function Show-Staging {
  Write-Title "Staging & Commits"
  Write-Host ""
  Write-Host "Commandes disponibles:"
  Write-Host "  1. git add . (stage tout)"
  Write-Host "  2. git add fichier (stage un fichier)"
  Write-Host "  3. git commit -m (créer un commit)"
  Write-Host "  4. git commit --amend (modifier le dernier)"
  Write-Host "  0. Retour au menu"
  Write-Host ""
  $choice = Read-Host "Choisissez (0-4)"
  
  switch ($choice) {
    "1" {
      Write-Cmd "git add ."
      git add .
      Write-Success "Tous les fichiers stagés"
      git status -s
    }
    "2" {
      $file = Read-Host "Fichier à stager"
      Write-Cmd "git add '$file'"
      git add $file
    }
    "3" {
      $msg = Read-Host "Message de commit"
      Write-Cmd "git commit -m '$msg'"
      git commit -m $msg
    }
    "4" {
      $msg = Read-Host "Nouveau message (ou vide)"
      if ([string]::IsNullOrWhiteSpace($msg)) {
        Write-Cmd "git commit --amend --no-edit"
        git commit --amend --no-edit
      } else {
        Write-Cmd "git commit --amend -m '$msg'"
        git commit --amend -m $msg
      }
    }
    "0" { Show-MainMenu }
    default { Write-Error "Choix invalide"; Start-Sleep 1; Show-Staging }
  }
  Read-Host "Appuyez sur Entrée pour continuer"
  Show-Staging
}

function Show-Branches {
  Write-Title "Branches"
  Write-Host ""
  Write-Host "Commandes disponibles:"
  Write-Host "  1. git branch (lister)"
  Write-Host "  2. git branch -a (toutes)"
  Write-Host "  3. git switch (changer)"
  Write-Host "  4. git switch -c (créer)"
  Write-Host "  5. git merge (fusionner)"
  Write-Host "  6. git rebase (rebase)"
  Write-Host "  0. Retour au menu"
  Write-Host ""
  $choice = Read-Host "Choisissez (0-6)"
  
  switch ($choice) {
    "1" {
      Write-Cmd "git branch"
      git branch
    }
    "2" {
      Write-Cmd "git branch -a"
      git branch -a
    }
    "3" {
      $branch = Read-Host "Branche"
      Write-Cmd "git switch '$branch'"
      git switch $branch
    }
    "4" {
      $branch = Read-Host "Nom de la nouvelle branche"
      Write-Cmd "git switch -c '$branch'"
      git switch -c $branch
    }
    "5" {
      $branch = Read-Host "Branche à fusionner"
      Write-Cmd "git merge '$branch'"
      git merge $branch
    }
    "6" {
      $branch = Read-Host "Branche pour rebase"
      Write-Cmd "git rebase '$branch'"
      git rebase $branch
    }
    "0" { Show-MainMenu }
    default { Write-Error "Choix invalide"; Start-Sleep 1; Show-Branches }
  }
  Read-Host "Appuyez sur Entrée pour continuer"
  Show-Branches
}

function Show-Remote {
  Write-Title "Synchronisation Distante"
  Write-Host ""
  Write-Host "Commandes disponibles:"
  Write-Host "  1. git remote (lister)"
  Write-Host "  2. git remote -v (avec URLs)"
  Write-Host "  3. git fetch (télécharger)"
  Write-Host "  4. git pull (télécharger + fusionner)"
  Write-Host "  5. git push (envoyer)"
  Write-Host "  6. git remote add (ajouter)"
  Write-Host "  0. Retour au menu"
  Write-Host ""
  $choice = Read-Host "Choisissez (0-6)"
  
  switch ($choice) {
    "1" {
      Write-Cmd "git remote"
      git remote
    }
    "2" {
      Write-Cmd "git remote -v"
      git remote -v
    }
    "3" {
      Write-Cmd "git fetch"
      git fetch 2>&1 | Select-Object -First 20
    }
    "4" {
      Write-Cmd "git pull"
      git pull 2>&1 | Select-Object -First 20
    }
    "5" {
      Write-Cmd "git push"
      git push 2>&1 | Select-Object -First 20
    }
    "6" {
      $name = Read-Host "Nom du remote"
      $url = Read-Host "URL du remote"
      Write-Cmd "git remote add '$name' '$url'"
      git remote add $name $url
    }
    "0" { Show-MainMenu }
    default { Write-Error "Choix invalide"; Start-Sleep 1; Show-Remote }
  }
  Read-Host "Appuyez sur Entrée pour continuer"
  Show-Remote
}

function Show-History {
  Write-Title "Historique & Recherche"
  Write-Host ""
  Write-Host "Commandes disponibles:"
  Write-Host "  1. git log (historique)"
  Write-Host "  2. git log --oneline (compacte)"
  Write-Host "  3. git log --graph --all (graphique)"
  Write-Host "  4. git show (détails)"
  Write-Host "  5. git log -p (avec diff)"
  Write-Host "  6. git blame (qui a modifié)"
  Write-Host "  0. Retour au menu"
  Write-Host ""
  $choice = Read-Host "Choisissez (0-6)"
  
  switch ($choice) {
    "1" {
      Write-Cmd "git log (10)"
      git log -10
    }
    "2" {
      Write-Cmd "git log --oneline (20)"
      git log --oneline -20
    }
    "3" {
      Write-Cmd "git log --graph --all (20)"
      git log --oneline --graph --all -20
    }
    "4" {
      Write-Cmd "git show HEAD"
      git show HEAD | Select-Object -First 30
    }
    "5" {
      $file = Read-Host "Fichier"
      Write-Cmd "git log -p '$file' (5)"
      git log -p $file -5
    }
    "6" {
      $file = Read-Host "Fichier"
      Write-Cmd "git blame '$file'"
      git blame $file | Select-Object -First 20
    }
    "0" { Show-MainMenu }
    default { Write-Error "Choix invalide"; Start-Sleep 1; Show-History }
  }
  Read-Host "Appuyez sur Entrée pour continuer"
  Show-History
}

function Show-Annulation {
  Write-Title "Annulation & Correction"
  Write-Host ""
  Write-Host "Commandes disponibles:"
  Write-Host "  1. git reset (déstager)"
  Write-Host "  2. git reset HEAD~1 (revenir)"
  Write-Host "  3. git revert (annuler commit)"
  Write-Host "  4. git restore (restaurer)"
  Write-Host "  5. git clean -n (fichiers à supprimer)"
  Write-Host "  0. Retour au menu"
  Write-Host ""
  $choice = Read-Host "Choisissez (0-5)"
  
  switch ($choice) {
    "1" {
      Write-Cmd "git reset"
      git reset
      Write-Success "Fichiers déstages"
    }
    "2" {
      Write-Error "ATTENTION: Annule le dernier commit"
      $confirm = Read-Host "Êtes-vous sûr? (y/n)"
      if ($confirm -eq "y") {
        Write-Cmd "git reset --soft HEAD~1"
        git reset --soft HEAD~1
        Write-Success "Annulé"
      }
    }
    "3" {
      $commit = Read-Host "Commit hash"
      Write-Cmd "git revert '$commit'"
      git revert $commit
    }
    "4" {
      $file = Read-Host "Fichier"
      Write-Cmd "git restore '$file'"
      git restore $file
    }
    "5" {
      Write-Cmd "git clean -n"
      git clean -n
    }
    "0" { Show-MainMenu }
    default { Write-Error "Choix invalide"; Start-Sleep 1; Show-Annulation }
  }
  Read-Host "Appuyez sur Entrée pour continuer"
  Show-Annulation
}

function Show-Stash {
  Write-Title "Stash (Sauvegardes Temporaires)"
  Write-Host ""
  Write-Host "Commandes disponibles:"
  Write-Host "  1. git stash (sauvegarder)"
  Write-Host "  2. git stash list (lister)"
  Write-Host "  3. git stash apply (appliquer)"
  Write-Host "  4. git stash pop (appliquer+supprimer)"
  Write-Host "  5. git stash show (voir)"
  Write-Host "  0. Retour au menu"
  Write-Host ""
  $choice = Read-Host "Choisissez (0-5)"
  
  switch ($choice) {
    "1" {
      $msg = Read-Host "Message (optionnel)"
      if ([string]::IsNullOrWhiteSpace($msg)) {
        Write-Cmd "git stash"
        git stash
      } else {
        Write-Cmd "git stash save '$msg'"
        git stash save $msg
      }
    }
    "2" {
      Write-Cmd "git stash list"
      git stash list
    }
    "3" {
      Write-Cmd "git stash apply"
      git stash apply
    }
    "4" {
      Write-Cmd "git stash pop"
      git stash pop
    }
    "5" {
      Write-Cmd "git stash show"
      git stash show
    }
    "0" { Show-MainMenu }
    default { Write-Error "Choix invalide"; Start-Sleep 1; Show-Stash }
  }
  Read-Host "Appuyez sur Entrée pour continuer"
  Show-Stash
}

function Show-Tags {
  Write-Title "Tags & Release"
  Write-Host ""
  Write-Host "Commandes disponibles:"
  Write-Host "  1. git tag (lister)"
  Write-Host "  2. git tag nom (créer)"
  Write-Host "  3. git tag -a (annoté)"
  Write-Host "  4. git show tag (voir)"
  Write-Host "  5. git push --tags (pousser)"
  Write-Host "  0. Retour au menu"
  Write-Host ""
  $choice = Read-Host "Choisissez (0-5)"
  
  switch ($choice) {
    "1" {
      Write-Cmd "git tag"
      git tag
    }
    "2" {
      $tag = Read-Host "Nom du tag (ex: v1.0.0)"
      Write-Cmd "git tag '$tag'"
      git tag $tag
    }
    "3" {
      $tag = Read-Host "Nom du tag"
      $msg = Read-Host "Message"
      Write-Cmd "git tag -a '$tag' -m '$msg'"
      git tag -a $tag -m $msg
    }
    "4" {
      $tag = Read-Host "Nom du tag"
      Write-Cmd "git show '$tag'"
      git show $tag
    }
    "5" {
      Write-Cmd "git push origin --tags"
      git push origin --tags
    }
    "0" { Show-MainMenu }
    default { Write-Error "Choix invalide"; Start-Sleep 1; Show-Tags }
  }
  Read-Host "Appuyez sur Entrée pour continuer"
  Show-Tags
}

function Show-Workflow {
  Write-Title "Workflow Complet (Scénario Pratique)"
  Write-Host ""
  Write-Host "Flux de travail typique:"
  Write-Host ""
  Write-Host "1. Créer une branche feature"
  Write-Host "2. Faire des changements"
  Write-Host "3. Committer les changements"
  Write-Host "4. Mettre à jour depuis main"
  Write-Host "5. Pousser vers le remote"
  Write-Host "6. Fusionner avec main"
  Write-Host ""
  Read-Host "Appuyez sur Entrée pour voir un exemple..."
  
  Write-Title "Étape 1: Créer une branche"
  Write-Host "Vous êtes sur la branche: $(git rev-parse --abbrev-ref HEAD)"
  $branch = Read-Host "Nom de la nouvelle branche"
  Write-Cmd "git switch -c '$branch'"
  git switch -c $branch
  
  Write-Title "Étape 2: Faire des changements"
  Write-Host "Fichiers modifiés:"
  git status -s
  Read-Host "Appuyez sur Entrée pour continuer..."
  
  Write-Title "Étape 3: Committer"
  $msg = Read-Host "Message de commit"
  git add . | Out-Null
  git commit -m $msg | Out-Null
  
  Write-Title "Étape 4: Log des commits"
  git log --oneline -5
  
  Write-Host ""
  Write-Success "Workflow démontré!"
  Read-Host "Appuyez sur Entrée pour retourner au menu..."
  Show-MainMenu
}

# Vérifier que c'est un dépôt Git
if (-not (Test-Path .git)) {
  Write-Error "Ce n'est pas un dépôt Git!"
  Write-Host "Exécutez d'abord: git init"
  exit 1
}

Show-MainMenu
