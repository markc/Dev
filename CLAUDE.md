# Dev Workspace

Parent repository with Git submodules. Single-command workspace setup while keeping projects independently clonable.

## Quick Start

```bash
# Full workspace
git clone --recurse-submodules git@github.com:markc/Dev.git

# Individual project
git clone git@github.com:markc/spe.git
```

## Submodule Workflow

Changes require two commits:
```bash
cd ~/Dev/spe && git add -A && git commit -m "msg" && git push   # 1. submodule
cd ~/Dev && git add spe && git commit -m "Update spe" && git push  # 2. parent
```

## Projects

| Project | Purpose | Repo |
|---------|---------|------|
| aitxt | ai.txt spec (aitxt.ing) | markc/aitxt |
| appmesh | Desktop automation via D-Bus/OSC | markc/appmesh |
| netserva | Infrastructure platform (Laravel) | netserva/monorepo |
| rentanet | Static business site (renta.net) | markc/rentanet |
| spe | PHP 8.5 tutorial (11 chapters) | markc/spe |

Related: `~/.ns/` (private ops notes), `~/.rc/` (dotfiles)

## Unified Dev Server

```bash
php -S localhost:8000 index.php
```

Routes by prefix: `/spe/`, `/rentanet/`, `/aitxt/`, `/netserva/`, `/appmesh/`

## Working Here

Prefer `cd ~/Dev/spe && claude` for focused work. Use `~/Dev` only for cross-project tasks.
