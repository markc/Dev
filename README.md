# Dev Workspace

Unified development workspace with Git submodules. Single-command setup while keeping projects independently clonable.

## Quick Start

```bash
# Full workspace (all projects)
git clone --recurse-submodules git@github.com:markc/Dev.git

# Individual project
git clone git@github.com:markc/spe.git
```

## Projects

| Project | Description | Links |
|---------|-------------|-------|
| **[aitxt](aitxt/)** | ai.txt specification standard | [aitxt.ing](https://aitxt.ing) · [ai.txt](aitxt/ai.txt) · [repo](https://github.com/markc/aitxt) |
| **[appmesh](appmesh/)** | Desktop automation via D-Bus/OSC | [ai.txt](appmesh/ai.txt) · [repo](https://github.com/markc/appmesh) |
| **[netserva](netserva/)** | Infrastructure platform (Laravel + Filament) | [ai.txt](netserva/ai.txt) · [repo](https://github.com/netserva/monorepo) |
| **[rentanet](rentanet/)** | Static business website | [renta.net](https://renta.net) · [ai.txt](rentanet/ai.txt) · [repo](https://github.com/markc/rentanet) |
| **[spe](spe/)** | PHP 8.5 micro-framework tutorial | [docs](https://markc.github.io/spe/) · [ai.txt](spe/ai.txt) · [repo](https://github.com/markc/spe) |

## Project Summaries

### aitxt
Universal standard for AI-readable context files. Like `robots.txt` for search engines, `ai.txt` tells AI assistants what something is about. Hosted at [aitxt.ing](https://aitxt.ing).

### appmesh
Desktop automation through application mesh networking - the modern successor to ARexx. Enables Claude Code to control desktop applications via D-Bus (clipboard, notifications, screenshots, window management) and OSC (audio production software).

### netserva
NetServa 3.0 Platform - comprehensive infrastructure management for web hosting. Built with Laravel 12 + Filament 4, featuring plugin-based packages for DNS, mail, web hosting, WireGuard VPN, and fleet management.

### rentanet
Static marketing website for RentaNet, an Australian web and email hosting company. Uses a modular CSS/JS architecture (base.css + site.css) that's shared across multiple projects.

### spe
Progressive PHP 8.5 micro-framework tutorial in 11 chapters. Teaches modern PHP features (pipe operator, asymmetric visibility) through building a complete web application. Includes legacy HCP code in `11-HCP/hcp-legacy/` for reference.

## Unified Dev Server

Serve all projects from a single PHP server:

```bash
cd ~/Dev && php -S localhost:8000 index.php
```

| URL | Project |
|-----|---------|
| [localhost:8000/](http://localhost:8000/) | Dev portal |
| [localhost:8000/spe/](http://localhost:8000/spe/) | spe tutorial |
| [localhost:8000/rentanet/](http://localhost:8000/rentanet/) | rentanet site |
| [localhost:8000/aitxt/](http://localhost:8000/aitxt/) | aitxt spec |
| [localhost:8000/netserva/](http://localhost:8000/netserva/) | netserva app |
| [localhost:8000/appmesh/](http://localhost:8000/appmesh/) | appmesh files |

## Submodule Workflow

Changes in submodules require two commits:

```bash
# 1. Commit in submodule
cd ~/Dev/spe && git add -A && git commit -m "msg" && git push

# 2. Update parent
cd ~/Dev && git add spe && git commit -m "Update spe" && git push
```

## AI Context

- [ai.txt](ai.txt) - AI-readable context for this workspace
- [CLAUDE.md](CLAUDE.md) - Instructions for Claude Code

Each project also has its own `ai.txt` and `CLAUDE.md`.
