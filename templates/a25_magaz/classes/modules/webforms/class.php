<?php
class webforms_custom extends def_module {
    public function send_pre() {
        $url = getRequest('ref_onsuccess');
        $data = getRequest('data');
        $webforms = cmsController::getInstance()->getModule('webforms');
        if (!empty($data['new']['phone1'])) {
            $webforms->redirect($url);
        } else {
            $webforms->send();
        }
    }
}