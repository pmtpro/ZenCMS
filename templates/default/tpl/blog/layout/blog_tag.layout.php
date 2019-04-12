<div class="tags">
    <?php foreach ($blog['tags'] as $tag): ?>
        <span>
            <a href="<?php echo $tag['full_url'] ?>"><?php echo $tag['tag'] ?></a>
        </span>
    <?php endforeach; ?>
</div>