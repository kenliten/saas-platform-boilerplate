<?php
declare(strict_types=1);

namespace SaaSPlatform\Core\Testing;

$files = glob(__DIR__ . '/../tests/*Test.php');
$failed = [];
$total = 0;
foreach ($files as $file) {
    require_once $file;
}

// Each test file should register tests by calling it() below.
// The global $TESTS array collects them.
global $TESTS;
$TESTS = $TESTS ?? [];

function it(string $description, callable $fn): void {
    global $TESTS;
    $TESTS[] = ['desc' => $description, 'fn' => $fn];
}

foreach ($TESTS as $t) {
    $total++;
    try {
        $res = $t['fn']();
        // allow either boolean true or no exception and truthy result
        if ($res === false) {
            throw new Exception('Assertion returned false');
        }
        echo "✔ " . $t['desc'] . PHP_EOL;
    } catch (Throwable $e) {
        $failed[] = ['desc' => $t['desc'], 'error' => $e];
        echo "✘ " . $t['desc'] . PHP_EOL;
        echo "  " . $e->getMessage() . " in " . $e->getFile() . ':' . $e->getLine() . PHP_EOL;
    }
}

echo PHP_EOL;
if ($failed) {
    echo count($failed) . " failed, " . ($total - count($failed)) . " passed, {$total} total." . PHP_EOL;
    exit(1);
}
echo "All tests passed — {$total} total." . PHP_EOL;
exit(0);
