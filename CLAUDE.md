# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Important: Project Focus

**Always `cd` into a project directory before starting Claude Code.** Each project has its own CLAUDE.md with specific instructions, patterns, and context. Running from `~/Dev` should only be used for cross-project investigation or comparison tasks.

```bash
# For focused work on a single project
cd ~/Dev/spe && claude

# Only for cross-project tasks (like today's cleanup)
cd ~/Dev && claude
```

## Project Portfolio

Seven focused projects, organized like chapters in a book:

| # | Project | Purpose | Tech Stack |
|---|---------|---------|------------|
| 1 | **aitxt** | ai.txt specification standard | Static site (aitxt.ing) |
| 2 | **appmesh** | Desktop app orchestration via D-Bus/OSC | PHP MCP server |
| 3 | **hcp** | Original Hosting Control Panel | Pure PHP + Bootstrap 5 |
| 4 | **netserva** | NetServa 3.0 infrastructure platform | Laravel 12 + Filament 4 |
| 5 | **rentanet** | RentaNet business website | Static HTML/CSS/JS |
| 6 | **spe** | PHP micro-framework tutorial (10 chapters) | Pure PHP 8.5 |
| 7 | **vnodes** | Server operations workspace | Markdown journals |

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

### 3. hcp
Original PHP-based Hosting Control Panel (reference implementation).
- **Purpose:** Reference for porting to `spe/11-HCP`
- **Key files:** `lib/php/plugins/*.php` (13 plugins: accounts, auth, domains, vmails, etc.)
- **Note:** Bootstrap 5, single-file PHP architecture

### 4. netserva
NetServa 3.0 Platform - comprehensive infrastructure management.
- **Stack:** Laravel 12 + Filament 4 + Pest 4 + phpseclib
- **Architecture:** Plugin-based packages (core, dns, mail, web, wg, fleet, etc.)
- **Packages:** Published on Packagist as `netserva/*`
- **Run:** `composer run dev`

### 5. rentanet
Static marketing website for Australian hosting company (renta.net).
- **Framework:** Modular CSS/JS architecture (base.* + site.*)
- **Key pattern:** `ai.txt` as content source, `base.txt`/`site.txt` for structure
- **Deploy:** Push to main triggers webhook

### 6. spe
Progressive PHP 8.5 micro-framework tutorial in 10 chapters.
- **Chapters:** 00-Tutorial through 10-YouTube, each builds on previous
- **Key features:** Pipe operator (`|>`), asymmetric visibility, CRUDL pattern
- **Docs:** https://markc.github.io/spe/
- **Run:** `php -S localhost:8000` from root

### 7. vnodes
Operations workspace for managing NetServa vnodes via SSH.
- **Structure:** Per-vnode folders with notes.md and journal entries
- **Workflow:** `sx <vnode>` → auto-creates folder → journals work
- **Key files:** `standards.md` (global conventions), `<vnode>/notes.md`

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
Used by: **hcp** (PHP plugins), **spe** (chapter plugins), **netserva** (Composer packages), **appmesh** (protocol plugins)
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
| `http://localhost:8000/hcp/` | hcp PHP app |
| `http://localhost:8000/netserva/` | netserva Laravel (public/) |
| `http://localhost:8000/appmesh/` | appmesh files |
| `http://localhost:8000/vnodes/` | vnodes markdown |

### How It Works

`index.php` routes requests by project prefix:
- **spe**: Delegates to spe's own router (handles chapters, docs)
- **Static sites** (rentanet, aitxt): Serves files directly
- **PHP projects** (hcp): Executes PHP files
- **Laravel** (netserva): Routes to public/ directory

No more juggling multiple servers on different ports!

## Git Status Overview

```bash
# Check all projects at once
for d in */; do echo "=== $d ===" && git -C "$d" status -sb 2>/dev/null; done
```

## When Improving Shared Components

1. **Identify the canonical source** - usually `spe/docs/base.css` or `spe/docs/base.js`
2. **Make the improvement there first**
3. **Test thoroughly** in the source project
4. **Roll out to other projects** - rentanet, Dev portal, spe chapters
5. **Note the change** so future sessions know about the update

This keeps all projects benefiting from ongoing refinements rather than diverging.

## Pending Items

- **spe**: 3 unpushed commits with PHP syntax errors (unescaped quotes in heredocs need fixing)
