#!/usr/bin/env bash
# Script bash pour synchroniser un dépôt Git local avec GitHub
# Usage: ./sync.sh status|configure-user|add-gitignore|untrack|set-https|set-ssh|pull|push|list-ignored|show-remote

ACTION=${1:-help}
REPO_ROOT=$(pwd)

show_help(){
  cat <<EOF
Usage: sync.sh <action>
Actions:
  status          Show git status (short)
  configure-user  Set local git user.email and user.name (env GIT_EMAIL, GIT_NAME)
  add-gitignore   Write a common .gitignore template (overwrite)
  untrack         git rm --cached on common paths (vendor, config, public/logs, logs, uploads)
  set-https       Configure credential helper (manager-core)
  set-ssh         Generate SSH key (ed25519) and show public key
  pull            Pull --rebase from remote default branch
  push            Push current local branch to origin
  list-ignored    List untracked ignored files and tracked files that match .gitignore
  show-remote     Show remote url(s)
  help            Show this message
EOF
}

git_status(){ git status -sb; }

configure_user(){
  if [ -n "$GIT_EMAIL" ]; then git config --local user.email "$GIT_EMAIL"; fi
  if [ -n "$GIT_NAME" ]; then git config --local user.name "$GIT_NAME"; fi
  git config --local user.email
  git config --local user.name
}

add_gitignore(){
  cat > .gitignore <<'EOG'
# OS
.DS_Store
Thumbs.db

# IDEs
/.idea/
/.vscode/

# Dependencies
/vendor/
/node_modules/

# Environment
*.env
config/

# Logs
/logs/
/public/logs/
/uploads/
*.log
EOG
  echo ".gitignore written (overwrite)."
}

untrack_paths(){
  PATHS=(vendor config public/logs logs uploads)
  for p in "${PATHS[@]}"; do
    if [ -e "$p" ]; then
      git rm -r --cached --ignore-unmatch "$p" || true
      echo "git rm --cached $p (if tracked)"
    else
      echo "Path not found: $p"
    fi
  done
  git add .gitignore
  git commit -m "Remove ignored files from repository and update .gitignore" || echo "No commit necessary or failed."
}

set_https(){
  git config --global credential.helper manager-core
  echo "Configured credential.helper manager-core (Windows). Use GitHub username and PAT when prompted."
}

set_ssh(){
  SSH_DIR="$HOME/.ssh"
  mkdir -p "$SSH_DIR"
  KEY="$SSH_DIR/id_ed25519"
  if [ ! -f "$KEY" ]; then
    ssh-keygen -t ed25519 -C "${GIT_EMAIL:-your_email@example.com}" -f "$KEY"
  else
    echo "SSH key already exists: $KEY"
  fi
  echo "Public key:"; cat "$KEY.pub"
  echo "Add it to GitHub > Settings > SSH and GPG keys"
}

detect_remote_default_branch(){
  sym=$(git ls-remote --symref origin HEAD 2>/dev/null)
  if [ -n "$sym" ]; then
    b=$(echo "$sym" | sed -n 's/.*ref: refs\/heads\/\([^ ]*\) HEAD/\1/p')
    if [ -n "$b" ]; then echo "$b"; return; fi
  fi
  heads=$(git ls-remote --heads origin 2>/dev/null | sed -n 's/.*refs\/heads\///p')
  if echo "$heads" | grep -q '^main$'; then echo main; return; fi
  if echo "$heads" | grep -q '^master$'; then echo master; return; fi
  echo "$heads" | head -n1
}

pull_remote(){
  branch=$(detect_remote_default_branch)
  if [ -z "$branch" ]; then echo "No remote branch detected. Skipping pull."; return; fi
  git pull --rebase origin "$branch"
}

push_remote(){
  branch=$(git rev-parse --abbrev-ref HEAD)
  if [ -z "$branch" ]; then echo "No local branch detected."; return; fi
  git push origin "$branch"
}

list_tracked_ignored(){
  git ls-files -i --exclude-standard --others
  git ls-files | git check-ignore -v --stdin || true
}

show_remote(){ git remote -v; }

case "$ACTION" in
  status) git_status ;;
  configure-user) configure_user ;;
  add-gitignore) add_gitignore ;;
  untrack) untrack_paths ;;
  set-https) set_https ;;
  set-ssh) set_ssh ;;
  pull) pull_remote ;;
  push) push_remote ;;
  list-ignored) list_tracked_ignored ;;
  show-remote) show_remote ;;
  help|*) show_help ;;
esac
