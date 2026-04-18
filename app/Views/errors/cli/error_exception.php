An Error Was Encountered: <?= isset($message) ? esc($message) : 'Unknown error' ?>

<?php if (isset($file) && isset($line)): ?>
File: <?= esc($file) ?>
Line: <?= esc($line) ?>
<?php endif; ?>