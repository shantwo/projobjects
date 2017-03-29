<?php if (sizeof(\Classes\Webforce3\Config\Config::getInstance()->getErrorList()) > 0) : ?>
	<div class="alert alert-danger" role="alert">
		<?= join('<br>'."\n", \Classes\Webforce3\Config\Config::getInstance()->getErrorList()); ?>
	</div>
<?php endif; ?>
<?php if (sizeof(\Classes\Webforce3\Config\Config::getInstance()->getWarningList()) > 0) : ?>
	<div class="alert alert-warning" role="alert">
		<?= join('<br>'."\n", \Classes\Webforce3\Config\Config::getInstance()->getWarningList()); ?>
	</div>
<?php endif; ?>
<?php if (sizeof(\Classes\Webforce3\Config\Config::getInstance()->getInfoList()) > 0) : ?>
	<div class="alert alert-info" role="alert">
		<?= join('<br>'."\n", \Classes\Webforce3\Config\Config::getInstance()->getInfoList()); ?>
	</div>
<?php endif; ?>
<?php if (sizeof(\Classes\Webforce3\Config\Config::getInstance()->getSuccessList()) > 0) : ?>
	<div class="alert alert-success" role="alert">
		<?= join('<br>'."\n", \Classes\Webforce3\Config\Config::getInstance()->getSuccessList()); ?>
	</div>
<?php endif; ?>