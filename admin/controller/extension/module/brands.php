<?php
class ControllerExtensionModuleBrands extends Controller {
	public function index() {

            $array = array(
            'oc' => $this,
            'heading_title' => 'Brands',
            'modulename' => 'brands',
                  'fields' => array(
                      array('type' => 'repeater', 'label' => 'Brands', 'name' => 'row_contents',
                            'fields' => array(
                                array('type' => 'image', 'label' => 'Image', 'name' => 'image'),
                                array('type' => 'text', 'label' => 'Name', 'name' => 'name'),
                                array('type' => 'text', 'label' => 'Link', 'name' => 'link'),
                            )
                      ),

                  ),
            );

        $this->modulehelper->init($array);
	}
}
