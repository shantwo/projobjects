<select<?php foreach ($attributesList as $attrName=>$attrValue) : ?> <?= $attrName ?>="<?= $attrValue ?>"<?php endforeach; ?>>
	<option value="">choose</option>
	<?php foreach ($selectValues as $selectValue=>$selectLabel) : ?><option value="<?= $selectValue ?>"<?php if ($selectValue == $selectedValue) : ?> selected="selected"<?php endif; ?>><?= $selectLabel ?></option>
	<?php endforeach; ?>
</select>