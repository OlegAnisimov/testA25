<?php
 class MoveContentTableDataAction extends Action {protected $hierarchyTypeId;public function execute() {$this->hierarchyTypeId = $this->getParam('hierarchy-type-id');if (!is_numeric($this->hierarchyTypeId)) {throw new Exception('Param "hierarchy-type-id" must be numeric');}$this->moveBranchedData();}public function rollback() {$vacf567c9c3d6cf7c6e2cc0ce108e0631 = $this->hierarchyTypeId;$vcda36002b56bb226dc93d3af6686772f = 'cms3_object_content_' . $vacf567c9c3d6cf7c6e2cc0ce108e0631;$vac5c74b64b4b8352ef2f181affb5ac2a = <<<SQL
TRUNCATE TABLE `{$vcda36002b56bb226dc93d3af6686772f}`
SQL;
INSERT INTO `{$vcda36002b56bb226dc93d3af6686772f}` SELECT * FROM `{$v958a0b05bca2609f7f255d48df986c7c}`
	WHERE `obj_id` IN (SELECT `id` FROM `cms3_objects` WHERE `type_id` IN ({$v18a72701d39d1412f966c9e87b188ecf}))
SQL;