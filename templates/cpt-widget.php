<?php if (count($pictures) > 0): ?>
	<div class="cptgallery-container">
		<ul class="rig columns-<?php echo $cptGalleryImgPerRow; ?>">
		<?php foreach ($pictures as $picture): ?>
				<li>
					<a href="<?php echo $picture['large']; ?>" title="<?php echo $picture['caption']; ?>" class="fancybox">
						<img src="<?php echo $picture['thumb']; ?>" />
					</a>
				</li>
		<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>