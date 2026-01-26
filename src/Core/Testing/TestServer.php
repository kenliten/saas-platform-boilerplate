<?php
declare(strict_types=1);

namespace SaaSPlatform\Core\Testing;

final class TestServer {
    private ?int $pid = null;
    private int $port;
    private string $docroot;
    private int $startTimeout = 5; // seconds

    public function __construct(int $port = 8080, ?string $docroot = null) {
        $this->port = $port;
        $this->docroot = $docroot ?: __DIR__ . '/../../../public/';
    }

    public function start(): void {
        if ($this->isWindows()) {
            throw new \RuntimeException('Windows not supported by this simple starter; use proc_open variant.');
        }

        $cmd = sprintf(
            'php -S 127.0.0.1:%d -t %s %s > /dev/null 2>&1 & echo $!',
            $this->port,
            escapeshellarg($this->docroot),
            escapeshellarg($this->docroot . 'index.php')
        );

        $out = trim((string) shell_exec($cmd));
        $pid = (int) $out;
        if ($pid <= 0) {
            throw new \RuntimeException('Failed to start server (cmd output: ' . $out . ')');
        }
        $this->pid = $pid;

        // wait for readiness
        $url = $this->url('/status');
        $deadline = time() + $this->startTimeout;
        while (time() < $deadline) {
            $r = @file_get_contents($url);
            if ($r !== false) return;
            usleep(100_000);
        }
        // if not ready, stop and error
        $this->stop();
        throw new \RuntimeException('Server did not become ready in time');
    }

    public function stop(): void {
        if ($this->pid === null) return;
        if (function_exists('posix_kill')) {
            posix_kill($this->pid, SIGTERM);
        } else {
            @exec('kill ' . (int)$this->pid);
        }
        $this->pid = null;
    }

    public function url(string $path = '/'): string {
        return "http://127.0.0.1:{$this->port}{$path}";
    }

    private function isWindows(): bool {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
