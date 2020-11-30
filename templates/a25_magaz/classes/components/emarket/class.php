<?php
	class emarket_custom extends def_module {
            public function print_invoice($order_id) {
                if (!$order_id) {
                    $order_id = getRequest('param0');
                }
                $objectsCollection = umiObjectsCollection::getInstance();
                $object = $objectsCollection->getObject($order_id);
                $orderId = $object->getId();
                $uri = "uobject://{$orderId}/?transform=sys-tpls/emarket-invoice.xsl";
                $result = file_get_contents($uri);
                $buffer = outputBuffer::current();
                $buffer->charset('utf-8');
                $buffer->contentType('text/html');
                $buffer->clear();
                $buffer->push($result);
                $buffer->end();
                return $result;
            }

            public function check_lk_pay() {
                session_start();

                $return = array();
                $return['result'] = 'false';
                if (isset($_SESSION['lk_ord']) && isset($_SESSION['lk_pay'])) {
                    $return['result'] = 'true';
                    $return['order_id'] = $_SESSION['lk_ord'];
                    $return['payment_id'] = $_SESSION['lk_pay'];
                }
                return $return;
            }

            public function get_lp_id(){
                $typesCollection = umiObjectTypesCollection::getInstance();
                $typeId = $typesCollection->getTypeIdByHierarchyTypeName('emarket', 'legal_person');
                return $typeId;
            }

            public function pay_from_lk(){
                $paymentId = getRequest('payment-id');
                $order_id = getRequest('order_id');

                $controller = cmsController::getInstance();
                $urlPrefix = $controller->getUrlPrefix() ? ($controller->getUrlPrefix() . '/') : '';

                if (!$order_id) {
                    $url = "{$this->pre_lang}/" . $urlPrefix . "emarket/personal/";
                    $this->redirect($url);
                }
                if (!$paymentId) {
                    $url = "{$this->pre_lang}/" . $urlPrefix . "emarket/personal/";
                    $this->redirect($url);
                }

                $order = order::get($order_id);
                $payment = payment::get($paymentId, $order);

                if ($payment instanceof payment) {
                        $order->setValue('payment_id', $paymentId);
                        $order->commit();

			return $payment->process($template);
                } else {
                        $url = "{$this->pre_lang}/" . $urlPrefix . "emarket/personal/";
                        $this->redirect($url);
                }
            }

            public function get_payments(){
                $sel = new selector('objects');
                $sel->types('hierarchy-type')->name('emarket', 'payment');
                $sel->option('load-all-props')->value(true);

                $result = array();
                foreach ($sel as $paym){
                    $item = array();
                    $item['attribute:id'] = $paym->id;
                    $item['attribute:name'] = $paym->name;
                    $result['nodes:item'][] = $item;
                }

                return $result;
            }

            public function summary_do(){
                $emarket = cmsController::getInstance()->getModule('emarket');
                $order = $emarket->getBasketOrder();
//                $order = $order_b->getObject();


                $paymentId = getRequest('payment-id');
                if (!$paymentId) {
                    $this->errorNewMessage(getLabel('error-emarket-choose-payment'));
                    $this->errorPanic();
                    return;
                }

                $payment = payment::get($paymentId, $order);

                $controller = cmsController::getInstance();
		$urlPrefix = $controller->getUrlPrefix() ? ($controller->getUrlPrefix() . '/') : '';


                if ($payment instanceof payment) {
                    $order->setValue('payment_id', $paymentId);
                    $order->commit();
                    $url = "{$this->pre_lang}/" . $urlPrefix . "emarket/summary/";
                } else {
                    $url = "{$this->pre_lang}/" . $urlPrefix . "emarket/purchase/payment/choose/";
                }
                $this->redirect($url);
            }

            public function summary($template = 'default'){
                $customer_id = (int) getCookie('customer-id');
                $emarket = cmsController::getInstance()->getModule('emarket');
                if (!permissionsCollection::getInstance()->isAuth() && !$customer_id) {
                    list($tpl_block_empty) = def_module::loadTemplates("emarket/" . $template, 'order_block_empty');

                    $result = array(
                        'attribute:id' => 'dummy',
                        'summary' => array('amount' => 0),
                        'steps' => $this->getPurchaseSteps($template, null)
                    );

                    return def_module::parseTemplate($tpl_block_empty, $result);
                }

                $order = $emarket->getBasketOrder();

                $order->refresh();

                return $emarket->order($order->getId(), $template);
            }

            public function saveInfo_custom() {
                $cmsController = cmsController::getInstance();
                session_start();
                if (isset($_SESSION['lk_ord']) && isset($_SESSION['lk_pay'])) {
                    unset($_SESSION['lk_ord']);
                    unset($_SESSION['lk_pay']);
                }

                $module = $cmsController->getModule('emarket');
                $order = $module->getBasketOrder(false);

                $data = $cmsController->getModule('data');
                $data->saveEditedObject(customer::get()->getId(), false, true);

                $addressId = getRequest('delivery-address');

                if ($addressId == 'new') {
                    $collection = umiObjectsCollection::getInstance();
                    $types = umiObjectTypesCollection::getInstance();
                    $typeId = $types->getTypeIdByHierarchyTypeName("emarket", "delivery_address");
                    $customer = customer::get();
                    $addressId = $collection->addObject("Address for customer #" . $customer->getId(), $typeId);
                    $data->saveEditedObjectWithIgnorePermissions($addressId, true, true);
                    $customer->delivery_addresses = array_merge($customer->delivery_addresses, array($addressId));
                }

                $order->delivery_address = $addressId;

                $deliveryId = getRequest('delivery-id');

                if ($deliveryId){
                    /**
                     * @var delivery $delivery
                     */
                    $delivery = delivery::get($deliveryId);
                    $deliveryPrice = (float) $delivery->getDeliveryPrice($order);
                    $order->setValue('delivery_id', $deliveryId);
                    $order->setValue('delivery_price', $deliveryPrice);
                }

                $order->setValue('payment_id', getRequest('payment-id'));
                $order->setValue('kommentarij_k_zakazu', getRequest('kommentarij_k_zakazu'));
                $order->refresh();

                $paymentId = getRequest('payment-id');

                if (!$paymentId) {
                    $module->errorNewMessage(getLabel('error-emarket-choose-payment'));
                    $module->errorPanic();
                }

                $payment = payment::get($paymentId, $order);

                if ($payment instanceof payment) {
                    $paymentName = $payment->getCodeName();
                    $url = "{$module->pre_lang}/" . $cmsController->getUrlPrefix() . "emarket/purchase/payment/{$paymentName}/";
                    if ($paymentName = 'receipt'){
                        $url .= '?redirect=1';
                    }
                } else {
                    $url = "{$module->pre_lang}/" . $cmsController->getUrlPrefix() . "emarket/cart/";
                }

                $module->redirect($url);
        }

        public function receipt_link($order_id){
                if ( !$order_id ) {
                        $order_id = getRequest('order_id');
                }
                $objects = umiObjectsCollection::getInstance();
                $object_order = $objects->getObject($order_id);

                $payment_id = $object_order->getValue('payment_id');
                if ( !$payment_id ) {
                        return "";
                }
                $payment_type_id = $objects->getObject($payment_id)->getValue('payment_type_id');
                $class_name = $objects->getObject($payment_type_id)->getValue('class_name');
                if($class_name == 'receipt'){
                  $customer_id = $object_order->getValue('customer_id');
                  $customer_mail = $objects->getObject($customer_id)->getValue('email');
                  $date = $object_order->order_date;
                  $getcode = sha1("{$customer_id}:{$customer_mail}:{$date}");
                  $url = "/emarket/receipt/{$order_id}/{$getcode}/";
                  return "<a href=\"{$url}\" target=\"_blank\" class=\"button-normal light-color middle-gradient dark-gradient-hover hide-on-mobile margin-bottom\">Платежная квитанция</a>";
                }else{
                  return "";
                }
        }

	};
?>