<?php
	class content_custom extends def_module {
            public function blank_transform(){
                return 1;
            }

	    public function get_merch_tab_list($page_id = false, $property_name = false, $offset = false){
                if (!$page_id){
                    $page_id = cmsController::getInstance()->getCurrentElementId();
                }
                if (!$property_name){
                    $property_name = 'rekomendovannye';
                }
                if (!$offset){
                    $offset = 0;
                }

                $hierarchy = umiHierarchy::getInstance();
                $page = $hierarchy->getElement($page_id);
                $page_list = $page->getValue($property_name);

                $total = count($page_list);
                $needle_pages = array_slice($page_list, $offset, 4);

                $emarket = cmsController::getInstance()->getModule('emarket');
                $system = &system_buildin_load("system");
                $objectsCollection = umiObjectsCollection::getInstance();
                $return = array();
                foreach($needle_pages as $needle_page){
                    $obj = $needle_page->getObject();
                    $price = $emarket->price($needle_page->id);
                    $page_price = array();
                    $page = array();
                    $parents = $hierarchy->getAllParents($needle_page->id);
                    $last_parent = array_pop($parents);
                    $parent = $hierarchy->getElement($last_parent);

                    $page['attribute:id'] = $needle_page->id;
                    $page['attribute:link'] = $needle_page->link;
                    $page['h1'] = $obj->getValue('h1');
                    $page['parent']['name'] = $parent->name;
                    $page['parent']['link'] = $parent->link;
                    $page['photo']['orginal'] = $obj->getValue('photo');
                    $page['photo']['thumb'] = $system->makeThumbnailFull('.'.$page['photo']['orginal'],150,170);

                    if (array_key_exists("discount", $price)) {
                        $discount_type = $objectsCollection->getObject($price['discount']['attribute:id']);
                        $discount_id = $discount_type->getValue('discount_modificator_id');
                        $discount_obj = $objectsCollection->getObject($discount_id);
                        $page['sticker']['name'] = 'Скидка '.$discount_obj->getValue('proc').'%';
                        $page_price['original'] = $price['price']['original'];
                        $page['attribute:discount'] = 'true';
                        $page['sticker']['color'] = 'red';
                    }else{
                        $page['attribute:discount'] = 'false';
                        $merch_prop_id = $obj->getValue('osobennost_tovara');
                        if ($merch_prop_id){
                            $merch_prop = $objectsCollection->getObject($merch_prop_id);
                            $page['sticker']['name'] = $merch_prop->name;
                            $sticker_id = $merch_prop->getValue('cvet_stikera');
                            $sticker = $objectsCollection->getObject($sticker_id);
                            $page['sticker']['color'] = $sticker->name;
                        }else{
                            $page['sticker']['color'] = 'false';
                        }
                    }
                    $page_price['actual'] = $price['price']['actual'];
                    $page['price'] = $page_price;

                    $return['pages']['nodes:page'][] = $page;
                }
                $return['total'] = $total;
                return $return;
            }
	};
?>