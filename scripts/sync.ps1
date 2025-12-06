<#
.SYNOPSIS
  Script PowerShell pour synchroniser un dépôt Git local avec GitHub.

USAGE
  .\sync.ps1 -Action status
  .\sync.ps1 -Action untrack
  .\sync.ps1 -Action set-https
  .\sync.ps1 -Action set-ssh
  .\sync.ps1 -Action pull
  .\sync.ps1 -Action push
  .\sync.ps1 -Action help

#>
param(
  [string]$Action = "help",
  [string[]]$PathsToUntrack = @('vendor','config','public/logs','logs','uploads'),
  [string]$Email,
  [string]$Name
)

function Show-Help {
  Write-Host "Usage: sync.ps1 -Action <status|configure-user|add-gitignore|untrack|set-https|set-ssh|pull|push|list-ignored|show-remote|help>"
  Write-Host "Examples:"; Write-Host "  .\sync.ps1 -Action status"
}

function Git-Status {
  git status -sb
}

function Configure-User {
  if ($Email) { git config --local user.email $Email; Write-Host "Set local user.email to $Email" }
  if ($Name)  { git config --local user.name $Name; Write-Host "Set local user.name to $Name" }
  git config --local user.email
  git config --local user.name
}

function Add-Gitignore {
  $gi = @(
    "# OS",
    ".DS_Store",
    "Thumbs.db",
    "# IDEs",
    "/.idea/",
    "/.vscode/",
    "# Dependencies",
    "/vendor/",
    "/node_modules/",
    "# Local config",
    "*.env",
    "config/",
    "# Logs and runtime",
    "/logs/",
    "/public/logs/",
    "/uploads/",
    "*.log"
  )
  $gi | Out-File -FilePath .gitignore -Encoding utf8
  Write-Host ".gitignore written (overwrite)."
}

function Untrack-Paths {
  foreach ($p in $PathsToUntrack) {
    if (Test-Path $p -PathType Any) {
      git rm -r --cached --ignore-unmatch $p
      Write-Host "git rm --cached $p (if it was tracked)"
    } else {
      Write-Host "Path not found: $p"
    }
  }
  git add .gitignore
  git commit -m "Remove ignored files from repository and update .gitignore" -q || Write-Host "No changes to commit or commit failed."
}

function Set-HTTPS {
  git config --global credential.helper manager-core
  Write-Host "Configured credential.helper manager-core (Windows). Use your GitHub username and PAT when prompted."
}

function Set-SSH {
  $sshDir = Join-Path $env:USERPROFILE '.ssh'
  if (-not (Test-Path $sshDir)) { New-Item -ItemType Directory -Path $sshDir | Out-Null }
  $keyPath = Join-Path $sshDir 'id_ed25519'
  if (-not (Test-Path $keyPath)) {
    Write-Host "Generating new SSH key at $keyPath..."
    ssh-keygen -t ed25519 -C "$($Email)" -f $keyPath
  } else { Write-Host "SSH key already exists: $keyPath" }
  $pub = Get-Content ($keyPath + '.pub') -Raw
  Write-Host "Public key (copy to GitHub > Settings > SSH and GPG keys):`n"; Write-Host $pub
  $current = git remote get-url origin 2>$null
  if ($current -and $current -match 'https://') {
    Write-Host "Remote is HTTPS. To switch to SSH run: git remote set-url origin git@github.com:<owner>/<repo>.git"
  } else { Write-Host "Remote already appears to be SSH or no origin set: $current" }
}

function Detect-Remote-DefaultBranch {
  $sym = git ls-remote --symref origin HEAD 2>$null
  if ($sym) {
    $m = $sym -match 'ref: refs/heads/(?<b>[^\s]+)\s+HEAD'
    if ($m) { return $Matches['b'] }
  }
  $heads = git ls-remote --heads origin 2>$null | Select-String -Pattern 'refs/heads/' | ForEach-Object { $_ -replace '.*refs/heads/','' }
  if ($heads -contains 'main') { return 'main' }
  if ($heads -contains 'master') { return 'master' }
  return $heads | Select-Object -First 1
}

function Pull-Remote {
  $branch = Detect-Remote-DefaultBranch
  if (-not $branch) { Write-Host "No remote branch detected. Skipping pull."; return }
  git pull --rebase origin $branch
}

function Push-Remote {
  $branch = git branch --show-current
  if (-not $branch) { Write-Host "Local branch not detected."; return }
  git push origin $branch
}

function List-Tracked-Ignored {
  git ls-files -i --exclude-standard --others
  # Also list tracked files matching .gitignore
  git ls-files | git check-ignore -v --stdin
}

function Show-Remote {
  git remote -v
}

switch ($Action.ToLower()) {
  'help' { Show-Help }
  'status' { Git-Status }
  'configure-user' { Configure-User }
  'add-gitignore' { Add-Gitignore }
  'untrack' { Untrack-Paths }
  'set-https' { Set-HTTPS }
  'set-ssh' { Set-SSH }
  'pull' { Pull-Remote }
  'push' { Push-Remote }
  'list-ignored' { List-Tracked-Ignored }
  'show-remote' { Show-Remote }
  default { Show-Help }
}
