<?php
	class catalog_custom extends def_module {
	    public function custom_catalog_search($catalog_id = false, $search = false, $per_page = false){
                $return = array();
                if (!$catalog_id || !$search){
                    $return['total'] = 0;
                }else{
                    if (!$per_page){
                        $per_page = 10;
                    }
                    $offset = getRequest('p');
                    if (!$offset){
                        $offset = 0;
                    }

                    $pages = new selector('pages');
                    $pages->types('hierarchy-type')->name('catalog', 'object');
                    $pages->where('hierarchy')->page($catalog_id)->childs(10);
                    $pages->where('name')->like('%' . $search . '%');
                    $pages->limit($offset*$per_page, $per_page);
                    $total = $pages->length();


                    $system = &system_buildin_load("system");

                    foreach ($pages as $page){
                        $page_arr = array();
                        $obj = $page->getObject();

                        $page_arr['attribute:id'] = $page->id;
                        $page_arr['attribute:link'] = $page->link;
                        $page_arr['h1'] = $obj->getValue('h1');
                        $page_arr['photo']['orginal'] = $obj->getValue('photo');
                        $page_arr['photo']['thumb'] = $system->makeThumbnailFull('.' . $page_arr['photo']['orginal'], 40, 40);

                        $return['pages']['nodes:page'][] = $page_arr;
                    }

                    $return['numpages'] = $system->numpages($total, $per_page);


                    $return['total'] = $total;
                    $return['per_page'] = $per_page;
                }
                return $return;
            }

            public function reload_catalog_inf($el_id = false){
                $return = array('result' => 'false');

                if ($el_id){
                    $hierarchy = umiHierarchy::getInstance();
                    $page = $hierarchy->getElement($el_id);

                    if ($page instanceof umiHierarchyElement){
                        $obj = $page->getObject();
                        $parents = $hierarchy->getAllParents($el_id);
                        $last_parent = array_pop($parents);
                        $parent = $hierarchy->getElement($last_parent);

                        $return = array('result' => 'true');



                        $emarket = cmsController::getInstance()->getModule('emarket');
                        $price = $emarket->price($el_id);

                        $sticker = '';
                        $price_str = '';
                        $img = 'false';

                        $objectsCollection = umiObjectsCollection::getInstance();
                        if (array_key_exists("discount", $price)) {
                            $discount_type = $objectsCollection->getObject($price['discount']['attribute:id']);
                            $discount_id = $discount_type->getValue('discount_modificator_id');
                            $discount_obj = $objectsCollection->getObject($discount_id);

                            $sticker = '<div class="ribbon-small ribbon-red"><div class="ribbon-inner"><span class="ribbon-text">Скидка ' . $discount_obj->getValue('proc') . '%</span><span class="ribbon-aligner"></span></div></div>';
                            $price_str .= '<del class="light-gradient middle-border dark-color">' . $price['price']['original'] . ' руб</del>';


                        }else{
                            $merch_prop_id = $obj->getValue('osobennost_tovara');
                            if ($merch_prop_id){
                                $merch_prop = $objectsCollection->getObject($merch_prop_id);
                                $sticker_id = $merch_prop->getValue('cvet_stikera');
                                $sticker = $objectsCollection->getObject($sticker_id);

                                $sticker = '<div class="ribbon-small ribbon-' . $sticker->name . '"><div class="ribbon-inner"><span class="ribbon-text">' . $merch_prop->name . '</span><span class="ribbon-aligner"></span></div></div>';
                            }
                        }

                        $price_str .= '<strong>' . $price['price']['actual'] . ' руб</strong>';

                        $photo = $obj->getValue('photo');

                        if ($photo){
                            $system = &system_buildin_load("system");
                            $img = $system->makeThumbnailFull('.'.$photo,150,170);
                        }

                        $return['sticker'] = $sticker;
                        $return['price'] = $price_str;
                        $return['img'] = $img;
                        $return['parent']['name'] = $parent->name;
                        $return['parent']['link'] = $parent->link;
                    }
                }

                return $return;
            }
	};
?>