<h1>Admin List</h1>
<ul>
    <?php foreach ($items as $item): ?>
        <li><?= htmlspecialchars(json_encode($item)) ?></li>
    <?php endforeach; ?>
</ul>