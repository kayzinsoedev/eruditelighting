<?php
class ControllerExtensionModuleBrands extends Controller {
	public function index() {
        $this->load->library('modulehelper');
        $Modulehelper = Modulehelper::get_instance($this->registry);
        $oc = $this;
        $language_id = $this->config->get('config_language_id');
        $modulename  = 'brands';

				$this->load->model('tool/image');

        $data['row_contents'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'row_contents');

		    return $this->load->view('extension/module/brands', $data);
    }

}
