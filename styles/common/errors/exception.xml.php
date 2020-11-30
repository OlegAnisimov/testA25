<?= '<?xml version="1.0" encoding="utf-8"?>'; ?>
<result xmlns:xlink="http://www.w3.org/TR/xlink">
	<data>
		<?php /** @var stdClass $templateException */ ?>
		<error code="<?= $templateException->code; ?>" type="<?= $templateException->type; ?>"><?= $templateException->message; ?></error>
		<?php

		if (DEBUG_SHOW_BACKTRACE):
			?>
			<backtrace><?php

			$traces = explode("\n", $templateException->traceAsString);

			foreach ($traces as $trace):
				?>
				<trace><?= $trace ?></trace><?php
			endforeach;

			?></backtrace><?php
		endif;
		?>
	</data>
</result>