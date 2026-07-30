<?php
// TEMPORARY BUILD SCRIPT - HAPUS SETELAH DIGUNAKAN
set_time_limit(300);
ini_set('max_execution_time', 300);

$projectPath = realpath(__DIR__ . '/..');

echo '<pre>';
echo "Project path: {$projectPath}\n\n";

// Detect node & npm path
$nodePaths = ['/usr/bin/node', '/usr/local/bin/node', '/usr/local/nvm/versions/node/current/bin/node'];
$npmPaths  = ['/usr/bin/npm', '/usr/local/bin/npm', '/usr/local/nvm/versions/node/current/bin/npm'];

$nodeBin = 'node';
foreach ($nodePaths as $p) {
    if (file_exists($p)) { $nodeBin = $p; break; }
}

$npmBin = 'npm';
foreach ($npmPaths as $p) {
    if (file_exists($p)) { $npmBin = $p; break; }
}

echo "Node: " . shell_exec("{$nodeBin} --version 2>&1");
echo "NPM:  " . shell_exec("{$npmBin} --version 2>&1");
// Cek node_modules ada atau tidak
$nodeModulesExists = is_dir("{$projectPath}/node_modules");
echo "node_modules exists: " . ($nodeModulesExists ? 'YES' : 'NO') . "\n\n";

if (!$nodeModulesExists) {
    echo "--- Running npm install ---\n\n";
    passthru("cd \"{$projectPath}\" && {$npmBin} install 2>&1", $installCode);
    echo "\nnpm install exit code: {$installCode}\n\n";
    if ($installCode !== 0) {
        echo "INSTALL FAILED - Stopping.\n</pre>";
        exit;
    }
}

echo "--- Running npm run build ---\n\n";

// Gunakan node langsung untuk bypass permission issue pada vite binary
$viteBin = "{$projectPath}/node_modules/vite/bin/vite.js";
if (file_exists($viteBin)) {
    $command = "cd \"{$projectPath}\" && {$nodeBin} \"{$viteBin}\" build 2>&1";
} else {
    shell_exec("chmod -R 755 \"{$projectPath}/node_modules/.bin/\" 2>&1");
    $command = "cd \"{$projectPath}\" && {$npmBin} run build 2>&1";
}
passthru($command, $returnCode);

echo "\n\nExit code: {$returnCode}\n";
echo $returnCode === 0 ? "\nBUILD SUCCESS" : "\nBUILD FAILED";
echo '</pre>';
