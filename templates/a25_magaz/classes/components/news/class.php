<?php
	class news_custom extends def_module {
           public function get_news_subjs($page_id = false){
                if(!$page_id){
                    $page_id = cmsController::getInstance()->getCurrentElementId();
                }

                $pages = new selector('pages');
                $pages->types('hierarchy-type')->name('news', 'item');
                $pages->where('hierarchy')->page($page_id)->childs(10);
                $pages->where('is_active')->isnotnull(true);
                $pages->where('subjects')->isnotnull(true);

                $subjects = array();
                foreach ($pages as $page){
                    $subjects = array_merge($subjects, $page->getValue('subjects'));
                }
                $subjects = array_unique($subjects);

                $objectsCollection = umiObjectsCollection::getInstance();

                $return = array();
                foreach($subjects as $subject_id){
                    $item = array();
                    $subject = $objectsCollection->getObject($subject_id);
                    $item['attribute:id'] = $subject_id;
                    $item['attribute:name'] = $subject->name;
                    $return['items']['nodes:item'][] = $item;
                }
                $return['page_id'] = $page_id;
                return $return;
            }

            public function get_news_list_by_subjs($page_id = false, $per_page = false){
                $subj_id = getRequest('subj');
                if(!$page_id){
                    $page_id = cmsController::getInstance()->getCurrentElementId();
                }

                if (!$per_page){
                    $regedit = regedit::getInstance();
                    $per_page = (int) $regedit->getVal("//modules/news/per_page");
                }

                $p = getRequest('p');
                if(!$p){
                    $p = 0;
                }

                $pages = new selector('pages');
                $pages->types('hierarchy-type')->name('news', 'item');
                $pages->where('hierarchy')->page($page_id)->childs(10);
                $pages->where('is_active')->isnotnull(true);
                if($subj_id){
                    $pages->where('subjects')->equals($subj_id);
                }
                $pages->limit($p*$per_page, $per_page);

                $total = $pages->length();

                $return = array();

                foreach ($pages as $page){
                    $item = array();
                    $item['attribute:id'] = $page->id;
                    $item['attribute:name'] = $page->name;
                    $item['attribute:link'] = $page->link;
                    $return['items']['nodes:item'][] = $item;
                }
                $return['total'] = $total;
                $return['per_page'] = $per_page;
                $return['page_id'] = $page_id;

                return $return;
            }

	};
?>