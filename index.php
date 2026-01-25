<?php declare(strict_types=1);
// Copyright (C) 2015-2026 Mark Constable <mc@netserva.org> (MIT License)
// Unified Dev Portal Router
// Usage: cd ~/Dev && php -S localhost:8000 index.php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = __DIR__;

// MIME types for static files
$mimeTypes = [
    'css' => 'text/css',
    'js' => 'text/javascript',
    'json' => 'application/json',
    'html' => 'text/html',
    'htm' => 'text/html',
    'xml' => 'application/xml',
    'txt' => 'text/plain',
    'md' => 'text/markdown',
    'webp' => 'image/webp',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'gif' => 'image/gif',
    'svg' => 'image/svg+xml',
    'ico' => 'image/x-icon',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf' => 'font/ttf',
    'pdf' => 'application/pdf',
];

// Serve static file with correct content type
function serveFile(string $path, array $mimeTypes): bool {
    if (!is_file($path)) return false;
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    header('Content-Type: ' . ($mimeTypes[$ext] ?? mime_content_type($path) ?: 'application/octet-stream'));
    readfile($path);
    return true;
}

// Project configurations: [document_root, has_php_router, router_file]
$projects = [
    'spe'       => ['spe', true, 'index.php'],           // PHP router with chapters
    'rentanet'  => ['rentanet', false, null],            // Static site
    'aitxt'     => ['aitxt', false, null],               // Static site
    'hcp'       => ['hcp', false, null],                 // PHP files served directly
    'netserva'  => ['netserva/public', false, null],     // Laravel public folder
    'appmesh'   => ['appmesh', false, null],             // Static + PHP files
    'vnodes'    => ['vnodes', false, null],              // Markdown files
];

// Route: /project/path...
if (preg_match('#^/([a-z]+)(/.*)?$#', $uri, $m)) {
    $project = $m[1];
    $subpath = $m[2] ?? '/';

    if (isset($projects[$project])) {
        [$docRoot, $hasRouter, $routerFile] = $projects[$project];
        $projectPath = "$base/$docRoot";

        // Projects with their own PHP router (spe)
        if ($hasRouter && $routerFile) {
            // Change to project directory so its router works correctly
            chdir($projectPath);
            $_SERVER['REQUEST_URI'] = $subpath;
            $_SERVER['SCRIPT_NAME'] = "/$project/$routerFile";

            // Let the project router handle it
            $result = require "$projectPath/$routerFile";
            if ($result !== false) exit;

            // Router returned false = serve static file
            if (is_file($projectPath . $subpath)) {
                return serveFile($projectPath . $subpath, $mimeTypes) || false;
            }
            exit;
        }

        // Static file serving
        $filePath = $projectPath . $subpath;

        // Try exact file
        if (is_file($filePath)) {
            // PHP files get executed
            if (pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
                chdir(dirname($filePath));
                return require $filePath;
            }
            return serveFile($filePath, $mimeTypes) || false;
        }

        // Try index files in directory
        if (is_dir($filePath)) {
            foreach (['index.html', 'index.php'] as $index) {
                if (is_file("$filePath/$index")) {
                    if ($index === 'index.php') {
                        chdir($filePath);
                        return require "$filePath/$index";
                    }
                    return serveFile("$filePath/$index", $mimeTypes) || false;
                }
            }
        }

        // 404 for project
        http_response_code(404);
        echo "404 Not Found: $uri";
        return true;
    }
}

// Root path: serve Dev portal
if ($uri === '/' || $uri === '/index.html') {
    return serveFile("$base/index.html", $mimeTypes) || false;
}

// Static files at Dev root (for portal assets)
if (is_file($base . $uri)) {
    return serveFile($base . $uri, $mimeTypes) || false;
}

// Smart asset resolution: check Referer to find project context
// This handles cases like /base.css when browsing /rentanet/
$referer = $_SERVER['HTTP_REFERER'] ?? '';
if ($referer && preg_match('#https?://[^/]+/([a-z]+)/#', $referer, $refMatch)) {
    $refProject = $refMatch[1];
    if (isset($projects[$refProject])) {
        [$docRoot] = $projects[$refProject];
        $refFile = "$base/$docRoot$uri";
        if (is_file($refFile)) {
            return serveFile($refFile, $mimeTypes) || false;
        }
    }
}

// Fallback 404
http_response_code(404);
echo "404 Not Found: $uri";
