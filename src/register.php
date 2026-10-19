<?php
/**
 * @copyright  Copyright (c) 2026 Tsumiki. labs
 * @license    MIT License
 * @link       https://github.com/tsumiki-labs
 **/
declare(strict_types=1);

$envName = 'TSUMIKI_BUILDER_CORE_PATH';
$corePath = __DIR__;

if (PHP_OS_FAMILY === 'Windows') {
    $command = sprintf('setx %s "%s"', escapeshellarg($envName), escapeshellarg($corePath));
    exec($command, $output, $result);
    if ($result !== 0) {
        echo "failed to register {$envName}." . PHP_EOL;
        exit(1);
    }
} elseif (PHP_OS_FAMILY === 'Linux') {
    $home = getenv('HOME');
    if (!$home) {
        echo 'HOME is not defined.' . PHP_EOL;
        exit(1);
    }
    $profile = $home . '/.profile';
    $content = is_file($profile) ? file_get_contents($profile) : '';
    if ($content === false) {
        echo "can not read {$profile}." . PHP_EOL;
        exit(1);
    }
    $content = preg_replace('/^export ' . $envName . '\s?=.*?$/m', '', $content);
    if (file_put_contents($profile, rtrim($content) . "\n\n" . $block) === false) {
        echo "failed to register{$profile}." . PHP_EOL;
        exit(1);
    }
} else {
    echo 'unsupported OS: ' . PHP_OS_FAMILY . PHP_EOL;
    exit(1);
}

echo "{$envName} registered: {$corePath}\n";
