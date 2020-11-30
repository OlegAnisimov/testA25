<?php
	class users_custom extends def_module {
            public function user_fail_redir(){
                $from_page = getRequest('from_page');
                if (!$from_page) {
                    $from_page = getServer('HTTP_REFERER');
                }else{
                    $from_page .= '?nolog=1';
                }
                $this->redirect($from_page ? $from_page : ($this->pre_lang . '/users/auth/'));
            }

            public function pre_registrate_do(){
                $_REQUEST['login'] = getRequest('email');
                return $this->registrate_do();
            }
	};
?>