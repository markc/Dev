# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Repository Architecture

This is a **parent repository with Git submodules**. Each project is an independent repo that can be cloned separately, but this parent provides single-command workspace setup.

### Clone Commands

```bash
# Full workspace (for Mark's backup/restore)
git clone --recurse-submodules git@github.com:markc/Dev.git

# Individual projects (for others)
git clone git@github.com:markc/aitxt.git
git clone git@github.com:markc/rentanet.git
git clone git@github.com:markc/spe.git
git clone git@github.com:markc/appmesh.git
git clone git@github.com:netserva/monorepo.git
```

### Submodule Workflow

**IMPORTANT:** When working in submodules, changes require TWO commits:

```bash
# 1. Commit and push in the submodule
cd ~/Dev/spe
git add -A && git commit -m "Your change" && git push

# 2. Update parent to track new submodule commit
cd ~/Dev
git add spe && git commit -m "Update spe submodule" && git push
```

**Pulling updates:**
```bash
cd ~/Dev
git pull
git submodule update --remote --merge
```

**Check all submodule status:**
```bash
git submodule status
# or more detail:
for d in */; do echo "=== $d ===" && git -C "$d" status -sb 2>/dev/null; done
```

## Important: Project Focus

**Always `cd` into a project directory before starting Claude Code.** Each project has its own CLAUDE.md with specific instructions, patterns, and context. Running from `~/Dev` should only be used for cross-project investigation or comparison tasks.

```bash
# For focused work on a single project
cd ~/Dev/spe && claude

# Only for cross-project tasks
cd ~/Dev && claude
```

## Project Portfolio

Five projects as Git submodules:

| # | Project | Purpose | Tech Stack | Repo |
|---|---------|---------|------------|------|
| 1 | **aitxt** | ai.txt specification standard | Static site (aitxt.ing) | markc/aitxt |
| 2 | **appmesh** | Desktop app orchestration via D-Bus/OSC | PHP MCP server | markc/appmesh |
| 3 | **netserva** | NetServa 3.0 infrastructure platform | Laravel 12 + Filament 4 | netserva/monorepo |
| 4 | **rentanet** | RentaNet business website | Static HTML/CSS/JS | markc/rentanet |
| 5 | **spe** | PHP micro-framework tutorial (11 chapters) | Pure PHP 8.5 | markc/spe |

### Related but Separate

| Location | Purpose | Visibility |
|----------|---------|------------|
| `~/.ns/` | NetServa vnode operations workspace | Private repo |
| `~/.rc/` | Shell configuration (dotfiles) | Public repo |

## Project Summaries

### 1. aitxt
Universal standard for AI-readable context files. Hosted at https://aitxt.ing.
- **Key files:** `ai.txt` (spec), `index.html` (docs), `aitxt` (discovery script)
- **Provides:** `/aitxt` slash command for Claude Code

### 2. appmesh
Desktop automation through application mesh networking - the modern successor to ARexx.
- **Architecture:** MCP server with protocol plugins (D-Bus, OSC)
- **Key files:** `server/appmesh-mcp.php`, `server/plugins/dbus.php`
- **Run:** `php server/appmesh-mcp.php` or via MCP config

### 3. netserva
NetServa 3.0 Platform - comprehensive infrastructure management.
- **Stack:** Laravel 12 + Filament 4 + Pest 4 + phpseclib
- **Architecture:** Plugin-based local packages (core, dns, mail, web, wg, fleet)
- **Note:** Packages use local path repositories, not Packagist
- **Run:** `composer run dev`

### 4. rentanet
Static marketing website for Australian hosting company (renta.net).
- **Framework:** Modular CSS/JS architecture (base.* + site.*)
- **Key pattern:** `ai.txt` as content source, `base.txt`/`site.txt` for structure
- **Path strategy:** Relative paths for dual-context serving (see rentanet/CLAUDE.md)
- **Deploy:** Push to main triggers webhook

### 5. spe
Progressive PHP 8.5 micro-framework tutorial in 11 chapters.
- **Chapters:** 00-Tutorial through 10-Htmx, plus 11-HCP
- **Key features:** Pipe operator (`|>`), asymmetric visibility, CRUDL pattern
- **Legacy reference:** `11-HCP/hcp-legacy/` contains original HCP for porting
- **Docs:** https://markc.github.io/spe/
- **Run:** `php -S localhost:8000` from root

## Cross-Pollination Philosophy

A key benefit of consolidating projects in `~/Dev` is **cross-pollination** - developing a feature in one project and rolling it out everywhere else.

### Example: Dual Sidebar App Shell
The responsive dual-sidebar layout with pinnable sidebars, theme toggle, and mobile-first design was developed in `spe/docs/` and is now being rolled out to:
- `spe/docs/index.html` - Documentation browser
- `rentanet/index.html` - Business website
- `spe/index.php` - PHP app shell
- Advanced spe chapters (09-Blog, 11-HCP)
- `~/Dev/index.html` - This portal

When improving the app shell (new features, bug fixes, accessibility), update all instances.

### Shared CSS Framework (base.css + site.css)
Used by: **rentanet**, **spe**, **Dev portal**
- `base.css` - Color-agnostic framework (~1700 lines: layouts, components, animations)
- `site.css` - Site-specific theming (colors, fonts, brand)
- Separation enables theme switching without touching framework code
- Improvements to base.css benefit all projects

### ai.txt Convention
Used by: **aitxt** (defines it), **rentanet**, **netserva**
- Plain text context for AI agents
- Prevents hallucination by providing authoritative facts
- Cascading discovery up directory tree

### Plugin Architecture
Used by: **spe** (chapter plugins), **netserva** (Composer packages), **appmesh** (protocol plugins)
- Self-contained modules with standard interface
- Discovery and registration patterns

## Unified Dev Server

A single PHP server serves ALL projects via the unified router:

```bash
cd ~/Dev && php -S localhost:8000 index.php
```

### URL Structure

| URL | Serves |
|-----|--------|
| `http://localhost:8000/` | Dev portal (index.html) |
| `http://localhost:8000/spe/` | spe index (delegates to spe's router) |
| `http://localhost:8000/spe/09-Blog/` | spe chapter 09 |
| `http://localhost:8000/rentanet/` | rentanet static site |
| `http://localhost:8000/aitxt/` | aitxt static site |
| `http://localhost:8000/netserva/` | netserva Laravel (public/) |
| `http://localhost:8000/appmesh/` | appmesh files |

### How It Works

`index.php` routes requests by project prefix:
- **spe**: Delegates to spe's own router (handles chapters, docs)
- **Static sites** (rentanet, aitxt): Serves files directly
- **Laravel** (netserva): Routes to public/ directory

No more juggling multiple servers on different ports!

## When Improving Shared Components

1. **Identify the canonical source** - usually `spe/docs/base.css` or `spe/docs/base.js`
2. **Make the improvement there first**
3. **Test thoroughly** in the source project
4. **Roll out to other projects** - rentanet, Dev portal, spe chapters
5. **Note the change** so future sessions know about the update

This keeps all projects benefiting from ongoing refinements rather than diverging.

## Session History

### 2026-01-25
- Reorganized Dev as submodule-based parent repo
- Moved `hcp` into `spe/11-HCP/hcp-legacy/` as reference material
- Moved `vnodes` to `~/.ns/` (private operations workspace)
- Created `markc/Dev` GitHub repo with 5 submodules
- Added relative path documentation to rentanet/CLAUDE.md
