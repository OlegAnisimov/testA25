<?php
 class umiDumpExporter extends umiExporter {public function export($v6f017b01ac7b836b216574ebb3f5d73c, $vd1051e3a7d64c17a9cba77188937d2cd) {$v8be74552df93e31bbdd6b36ed74bdb6a = new selector('pages');if (is_array($v6f017b01ac7b836b216574ebb3f5d73c) && umiCount($v6f017b01ac7b836b216574ebb3f5d73c)) {foreach ($v6f017b01ac7b836b216574ebb3f5d73c as $ve730db5c29b7ba34f4d465b01bd33c5e) {$v8be74552df93e31bbdd6b36ed74bdb6a->where('hierarchy')->page($ve730db5c29b7ba34f4d465b01bd33c5e->getId())->level(100);}}else {$v8be74552df93e31bbdd6b36ed74bdb6a->where('hierarchy')->page(0)->level(100);}$v6a7f245843454cf4f28ad7c5e2572aa2 = array_merge($v8be74552df93e31bbdd6b36ed74bdb6a->result(), $v6f017b01ac7b836b216574ebb3f5d73c);$v6a7f245843454cf4f28ad7c5e2572aa2 = array_diff($v6a7f245843454cf4f28ad7c5e2572aa2, $vd1051e3a7d64c17a9cba77188937d2cd);return $this->getUmiDump($v6a7f245843454cf4f28ad7c5e2572aa2);}protected function getUmiDump($v92ec19ffde05e15769b1bb3ee05ad745, $vaf721e88e6c0a612be51c329cb2bc12a = false) {if (!$vaf721e88e6c0a612be51c329cb2bc12a) {$vaf721e88e6c0a612be51c329cb2bc12a = $this->getSourceName();}$ved780287e302ec3b9fd3c5e78771919f = new xmlExporter($vaf721e88e6c0a612be51c329cb2bc12a);$ved780287e302ec3b9fd3c5e78771919f->addBranches($v92ec19ffde05e15769b1bb3ee05ad745);$ved780287e302ec3b9fd3c5e78771919f->setIgnoreRelations();$result = $ved780287e302ec3b9fd3c5e78771919f->execute();return $result->saveXML();}}