# Dev Workspace

Parent repository with Git submodules. See [ai.txt](ai.txt) for AI-readable context, [README.md](README.md) for full documentation.

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
cd ~/Dev/spe && git add -A && git commit -m "msg" && git push   # submodule
cd ~/Dev && git add spe && git commit -m "Update spe" && git push  # parent
```

## Projects

| Project | Purpose | ai.txt |
|---------|---------|--------|
| aitxt | ai.txt spec (aitxt.ing) | [aitxt/ai.txt](aitxt/ai.txt) |
| appmesh | Desktop automation via D-Bus/OSC | [appmesh/ai.txt](appmesh/ai.txt) |
| netserva | Infrastructure platform (Laravel) | [netserva/ai.txt](netserva/ai.txt) |
| rentanet | Static business site (renta.net) | [rentanet/ai.txt](rentanet/ai.txt) |
| spe | PHP 8.5 tutorial (11 chapters) | [spe/ai.txt](spe/ai.txt) |

Related: `~/.ns/` (private ops), `~/.rc/` (dotfiles)

## Unified Dev Server

```bash
php -S localhost:8000 index.php
```

Routes: `/spe/`, `/rentanet/`, `/aitxt/`, `/netserva/`, `/appmesh/`

## Working Here

Prefer `cd ~/Dev/spe && claude` for focused work. Use `~/Dev` only for cross-project tasks.
