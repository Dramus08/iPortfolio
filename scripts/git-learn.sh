#!/usr/bin/env bash
# Script interactif pour apprendre les commandes Git
# Usage: ./git-learn.sh

set -e

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
MAGENTA='\033[0;35m'
NC='\033[0m' # No Color

# Fonctions de couleur
print_title(){ echo -e "${BLUE}=== $1 ===${NC}"; }
print_success(){ echo -e "${GREEN}✓ $1${NC}"; }
print_error(){ echo -e "${RED}✗ $1${NC}"; }
print_info(){ echo -e "${YELLOW}ℹ $1${NC}"; }
print_cmd(){ echo -e "${MAGENTA}$ $1${NC}"; }

show_main_menu() {
  clear
  print_title "Git Learning Interactive Script"
  echo ""
  echo "Catégories:"
  echo "  1. Configuration (config, user, email)"
  echo "  2. Inspection (status, diff, log)"
  echo "  3. Staging & Commits (add, commit, amend)"
  echo "  4. Branches (branch, switch, merge)"
  echo "  5. Remote (remote, push, pull, fetch)"
  echo "  6. Historique & Recherche (log, show, blame)"
  echo "  7. Annulation (reset, revert, clean)"
  echo "  8. Stash (sauvegardes temporaires)"
  echo "  9. Tags & Release (tag, release)"
  echo " 10. Workflow complet (scénario pratique)"
  echo "  0. Quitter"
  echo ""
  read -p "Choisissez une catégorie (0-10): " choice
  case $choice in
    1) show_configuration ;;
    2) show_inspection ;;
    3) show_staging ;;
    4) show_branches ;;
    5) show_remote ;;
    6) show_history ;;
    7) show_annulation ;;
    8) show_stash ;;
    9) show_tags ;;
    10) show_workflow ;;
    0) echo "Au revoir!"; exit 0 ;;
    *) print_error "Choix invalide"; sleep 1; show_main_menu ;;
  esac
}

show_configuration() {
  print_title "Git Configuration"
  echo ""
  echo "Commandes disponibles:"
  echo "  1. Configurer l'email local"
  echo "  2. Configurer le nom local"
  echo "  3. Voir la configuration"
  echo "  4. Configurer globalement"
  echo "  0. Retour au menu"
  echo ""
  read -p "Choisissez (0-4): " choice
  case $choice in
    1)
      read -p "Email: " email
      print_cmd "git config --local user.email '$email'"
      git config --local user.email "$email"
      print_success "Email configuré: $(git config user.email)"
      ;;
    2)
      read -p "Nom: " name
      print_cmd "git config --local user.name '$name'"
      git config --local user.name "$name"
      print_success "Nom configuré: $(git config user.name)"
      ;;
    3)
      print_cmd "git config --list"
      git config --list | grep -E "^user\.|^core\." | head -20
      ;;
    4)
      read -p "Email global: " email
      print_cmd "git config --global user.email '$email'"
      git config --global user.email "$email"
      print_success "Email global configuré"
      ;;
    0) show_main_menu ;;
    *) print_error "Choix invalide"; sleep 1; show_configuration ;;
  esac
  read -p "Appuyez sur Entrée pour continuer..."
  show_configuration
}

show_inspection() {
  print_title "Inspection du dépôt"
  echo ""
  echo "Commandes disponibles:"
  echo "  1. git status (voir l'état)"
  echo "  2. git status -s (compacte)"
  echo "  3. git diff (changements non stagés)"
  echo "  4. git diff --staged (changements stagés)"
  echo "  5. git ls-files (fichiers suivis)"
  echo "  0. Retour au menu"
  echo ""
  read -p "Choisissez (0-5): " choice
  case $choice in
    1)
      print_cmd "git status"
      git status
      ;;
    2)
      print_cmd "git status -s"
      git status -s
      ;;
    3)
      print_cmd "git diff"
      git diff | head -50 || print_info "Aucun changement non stagé"
      ;;
    4)
      print_cmd "git diff --staged"
      git diff --staged | head -50 || print_info "Aucun changement stagé"
      ;;
    5)
      print_cmd "git ls-files | head -20"
      git ls-files | head -20
      echo "... (et plus)"
      ;;
    0) show_main_menu ;;
    *) print_error "Choix invalide"; sleep 1; show_inspection ;;
  esac
  read -p "Appuyez sur Entrée pour continuer..."
  show_inspection
}

show_staging() {
  print_title "Staging & Commits"
  echo ""
  echo "Commandes disponibles:"
  echo "  1. git add . (stage tout)"
  echo "  2. git add fichier (stage un fichier)"
  echo "  3. git commit -m (créer un commit)"
  echo "  4. git commit --amend (modifier le dernier)"
  echo "  0. Retour au menu"
  echo ""
  read -p "Choisissez (0-4): " choice
  case $choice in
    1)
      print_cmd "git add ."
      git add .
      print_success "Tous les fichiers stagés"
      git status -s
      ;;
    2)
      read -p "Fichier à stager: " file
      print_cmd "git add '$file'"
      git add "$file" || print_error "Fichier introuvable"
      ;;
    3)
      read -p "Message de commit: " msg
      print_cmd "git commit -m '$msg'"
      git commit -m "$msg" || print_error "Aucun fichier stagé"
      ;;
    4)
      read -p "Nouveau message (ou vide pour garder): " msg
      if [ -z "$msg" ]; then
        print_cmd "git commit --amend --no-edit"
        git commit --amend --no-edit || print_error "Aucun changement à amender"
      else
        print_cmd "git commit --amend -m '$msg'"
        git commit --amend -m "$msg" || print_error "Aucun changement à amender"
      fi
      ;;
    0) show_main_menu ;;
    *) print_error "Choix invalide"; sleep 1; show_staging ;;
  esac
  read -p "Appuyez sur Entrée pour continuer..."
  show_staging
}

show_branches() {
  print_title "Branches"
  echo ""
  echo "Commandes disponibles:"
  echo "  1. git branch (lister les branches)"
  echo "  2. git branch -a (toutes les branches)"
  echo "  3. git switch branche (changer de branche)"
  echo "  4. git switch -c branche (créer et basculer)"
  echo "  5. git merge branche (fusionner)"
  echo "  6. git rebase branche (rebase)"
  echo "  0. Retour au menu"
  echo ""
  read -p "Choisissez (0-6): " choice
  case $choice in
    1)
      print_cmd "git branch"
      git branch
      ;;
    2)
      print_cmd "git branch -a"
      git branch -a
      ;;
    3)
      read -p "Branche: " branch
      print_cmd "git switch '$branch'"
      git switch "$branch" || print_error "Branche introuvable"
      ;;
    4)
      read -p "Nom de la nouvelle branche: " branch
      print_cmd "git switch -c '$branch'"
      git switch -c "$branch" || print_error "Erreur lors de la création"
      ;;
    5)
      read -p "Branche à fusionner: " branch
      print_cmd "git merge '$branch'"
      git merge "$branch" || print_error "Erreur lors de la fusion"
      ;;
    6)
      read -p "Branche pour rebase: " branch
      print_cmd "git rebase '$branch'"
      git rebase "$branch" || print_error "Erreur lors du rebase"
      ;;
    0) show_main_menu ;;
    *) print_error "Choix invalide"; sleep 1; show_branches ;;
  esac
  read -p "Appuyez sur Entrée pour continuer..."
  show_branches
}

show_remote() {
  print_title "Synchronisation Distante"
  echo ""
  echo "Commandes disponibles:"
  echo "  1. git remote (lister les remotes)"
  echo "  2. git remote -v (avec les URLs)"
  echo "  3. git fetch (télécharger)"
  echo "  4. git pull (télécharger + fusionner)"
  echo "  5. git push (envoyer)"
  echo "  6. git remote add (ajouter un remote)"
  echo "  0. Retour au menu"
  echo ""
  read -p "Choisissez (0-6): " choice
  case $choice in
    1)
      print_cmd "git remote"
      git remote
      ;;
    2)
      print_cmd "git remote -v"
      git remote -v
      ;;
    3)
      print_cmd "git fetch"
      git fetch 2>&1 | head -20 || print_info "Aucun remote configuré"
      ;;
    4)
      print_cmd "git pull"
      git pull 2>&1 | head -20 || print_info "Aucun remote configuré"
      ;;
    5)
      print_cmd "git push"
      git push 2>&1 | head -20 || print_info "Aucun remote configuré"
      ;;
    6)
      read -p "Nom du remote: " name
      read -p "URL du remote: " url
      print_cmd "git remote add '$name' '$url'"
      git remote add "$name" "$url" || print_error "Erreur lors de l'ajout"
      ;;
    0) show_main_menu ;;
    *) print_error "Choix invalide"; sleep 1; show_remote ;;
  esac
  read -p "Appuyez sur Entrée pour continuer..."
  show_remote
}

show_history() {
  print_title "Historique & Recherche"
  echo ""
  echo "Commandes disponibles:"
  echo "  1. git log (historique complet)"
  echo "  2. git log --oneline (compacte)"
  echo "  3. git log --oneline --graph --all (graphique)"
  echo "  4. git show commit (détails d'un commit)"
  echo "  5. git log -p fichier (historique d'un fichier)"
  echo "  6. git blame fichier (qui a modifié quoi)"
  echo "  0. Retour au menu"
  echo ""
  read -p "Choisissez (0-6): " choice
  case $choice in
    1)
      print_cmd "git log (10 derniers)"
      git log -10
      ;;
    2)
      print_cmd "git log --oneline (20 derniers)"
      git log --oneline -20
      ;;
    3)
      print_cmd "git log --oneline --graph --all (20)"
      git log --oneline --graph --all -20
      ;;
    4)
      print_cmd "git show HEAD"
      git show HEAD | head -30
      ;;
    5)
      read -p "Fichier: " file
      print_cmd "git log -p '$file' (5 derniers)"
      git log -p "$file" -5 || print_error "Fichier introuvable"
      ;;
    6)
      read -p "Fichier: " file
      print_cmd "git blame '$file'"
      git blame "$file" | head -20 || print_error "Fichier introuvable"
      ;;
    0) show_main_menu ;;
    *) print_error "Choix invalide"; sleep 1; show_history ;;
  esac
  read -p "Appuyez sur Entrée pour continuer..."
  show_history
}

show_annulation() {
  print_title "Annulation & Correction"
  echo ""
  echo "Commandes disponibles:"
  echo "  1. git reset (déstager tout)"
  echo "  2. git reset HEAD~1 (revenir au dernier commit)"
  echo "  3. git revert commit (annuler un commit)"
  echo "  4. git restore fichier (restaurer un fichier)"
  echo "  5. git clean -n (voir les fichiers à supprimer)"
  echo "  0. Retour au menu"
  echo ""
  read -p "Choisissez (0-5): " choice
  case $choice in
    1)
      print_cmd "git reset"
      git reset
      print_success "Tous les fichiers sont déstages"
      ;;
    2)
      print_error "ATTENTION: Cette commande annule le dernier commit"
      read -p "Êtes-vous sûr? (y/n): " confirm
      if [ "$confirm" = "y" ]; then
        print_cmd "git reset --soft HEAD~1"
        git reset --soft HEAD~1
        print_success "Dernier commit annulé (changements conservés)"
      fi
      ;;
    3)
      read -p "Commit hash: " commit
      print_cmd "git revert '$commit'"
      git revert "$commit" || print_error "Commit introuvable"
      ;;
    4)
      read -p "Fichier: " file
      print_cmd "git restore '$file'"
      git restore "$file" || print_error "Fichier introuvable"
      ;;
    5)
      print_cmd "git clean -n"
      git clean -n || print_info "Aucun fichier non suivi"
      ;;
    0) show_main_menu ;;
    *) print_error "Choix invalide"; sleep 1; show_annulation ;;
  esac
  read -p "Appuyez sur Entrée pour continuer..."
  show_annulation
}

show_stash() {
  print_title "Stash (Sauvegardes Temporaires)"
  echo ""
  echo "Commandes disponibles:"
  echo "  1. git stash (sauvegarder les changements)"
  echo "  2. git stash list (lister les stash)"
  echo "  3. git stash apply (appliquer le dernier)"
  echo "  4. git stash pop (appliquer et supprimer)"
  echo "  5. git stash show (voir les changements)"
  echo "  0. Retour au menu"
  echo ""
  read -p "Choisissez (0-5): " choice
  case $choice in
    1)
      read -p "Message (optionnel): " msg
      if [ -z "$msg" ]; then
        print_cmd "git stash"
        git stash || print_info "Aucun changement à sauvegarder"
      else
        print_cmd "git stash save '$msg'"
        git stash save "$msg" || print_info "Aucun changement à sauvegarder"
      fi
      ;;
    2)
      print_cmd "git stash list"
      git stash list || print_info "Aucun stash"
      ;;
    3)
      print_cmd "git stash apply"
      git stash apply || print_info "Aucun stash"
      ;;
    4)
      print_cmd "git stash pop"
      git stash pop || print_info "Aucun stash"
      ;;
    5)
      print_cmd "git stash show"
      git stash show || print_info "Aucun stash"
      ;;
    0) show_main_menu ;;
    *) print_error "Choix invalide"; sleep 1; show_stash ;;
  esac
  read -p "Appuyez sur Entrée pour continuer..."
  show_stash
}

show_tags() {
  print_title "Tags & Release"
  echo ""
  echo "Commandes disponibles:"
  echo "  1. git tag (lister les tags)"
  echo "  2. git tag nom (créer un tag)"
  echo "  3. git tag -a nom -m 'msg' (créer un tag annoté)"
  echo "  4. git show tag (voir un tag)"
  echo "  5. git push origin --tags (pousser les tags)"
  echo "  0. Retour au menu"
  echo ""
  read -p "Choisissez (0-5): " choice
  case $choice in
    1)
      print_cmd "git tag"
      git tag || print_info "Aucun tag"
      ;;
    2)
      read -p "Nom du tag (ex: v1.0.0): " tag
      print_cmd "git tag '$tag'"
      git tag "$tag" || print_error "Erreur"
      ;;
    3)
      read -p "Nom du tag: " tag
      read -p "Message: " msg
      print_cmd "git tag -a '$tag' -m '$msg'"
      git tag -a "$tag" -m "$msg" || print_error "Erreur"
      ;;
    4)
      read -p "Nom du tag: " tag
      print_cmd "git show '$tag'"
      git show "$tag" || print_error "Tag introuvable"
      ;;
    5)
      print_cmd "git push origin --tags"
      git push origin --tags || print_info "Aucun remote"
      ;;
    0) show_main_menu ;;
    *) print_error "Choix invalide"; sleep 1; show_tags ;;
  esac
  read -p "Appuyez sur Entrée pour continuer..."
  show_tags
}

show_workflow() {
  print_title "Workflow Complet (Scénario Pratique)"
  echo ""
  echo "Flux de travail typique:"
  echo ""
  echo "1. Créer une branche feature"
  echo "2. Faire des changements"
  echo "3. Committer les changements"
  echo "4. Mettre à jour depuis main"
  echo "5. Pousser vers le remote"
  echo "6. Fusionner avec main"
  echo ""
  echo "Appuyez sur Entrée pour voir un exemple..."
  read
  
  print_title "Étape 1: Créer une branche"
  echo "Vous êtes sur la branche: $(git rev-parse --abbrev-ref HEAD)"
  read -p "Nom de la nouvelle branche: " branch
  print_cmd "git switch -c '$branch'"
  git switch -c "$branch" || print_error "Erreur"
  
  print_title "Étape 2: Faire des changements"
  echo "Fichiers modifiés:"
  git status -s
  read -p "Appuyez sur Entrée pour continuer..."
  
  print_title "Étape 3: Committer"
  read -p "Message de commit: " msg
  git add . || true
  git commit -m "$msg" || print_info "Aucun changement à committer"
  
  print_title "Étape 4: Log des commits"
  git log --oneline -5
  
  echo ""
  print_success "Workflow démontré!"
  read -p "Appuyez sur Entrée pour retourner au menu..."
  show_main_menu
}

# Point d'entrée
if [ ! -d .git ]; then
  print_error "Ce n'est pas un dépôt Git!"
  echo "Exécutez 'd'abord: git init"
  exit 1
fi

show_main_menu
