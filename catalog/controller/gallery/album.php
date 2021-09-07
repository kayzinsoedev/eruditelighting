<?php
class ControllerGalleryAlbum extends Controller {
  public function index() {

    $this->load->language('gallery/gallery');

	$this->load->model('catalog/gallimage');

	$data['breadcrumbs'] = array();

	$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_home'),
		'href' => $this->url->link('common/home')
	);

	$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_gallery'),
		'href' => $this->url->link('gallery/album')
	);



	$this->document->setTitle($this->language->get('heading_title'));

	$data['heading_title'] = $this->language->get('heading_title');

	// slick
	$this->document->addStyle('catalog/view/javascript/slick/slick.min.css');
	$this->document->addScript('catalog/view/javascript/slick/slick-custom.min.js');



	$data['column_left'] = $this->load->controller('common/column_left');
	$data['column_right'] = $this->load->controller('common/column_right');
	$data['content_top'] = $this->load->controller('common/content_top');
	$data['content_bottom'] = $this->load->controller('common/content_bottom');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');

	$data['cat_id'] = 1;

	if(isset($this->request->get['cat_id']) && $this->request->get['cat_id']) {
		$data['cat_id'] = $this->request->get['cat_id'];
	}

	$data['gallery_layout'] = $gallery_layout = $this->config->get('config_gallery_layout');

	$category_tab_layout = array('layout_1');

	$listing_by_category = false;
	if(in_array($gallery_layout , $category_tab_layout)) {
		$listing_by_category = true;
	}

	$data['gallalbums'] = array();

	$gallalbums = $this->model_catalog_gallimage->getGallalbums();

	if(isset($gallalbums) && $gallalbums) {
		foreach ($gallalbums as $gallalbum) {

			$gallalbum_info_current = array();

			$cat_link = '';
			$cat_link = $this->url->link('gallery/album','cat_id='.$gallalbum['gallimage_id']);

			$data['cat_title'] = '';
			// if($listing_by_category) {
				$gallalbum_info_current = $this->model_catalog_gallimage->getGallalbum($data['cat_id']);
				$current_cat_link = '';
				$current_cat_link = $this->url->link('gallery/album','cat_id='.$data['cat_id']);

				$data['cat_title'] =  $gallalbum_info_current['name'];
			// }

			$gallalbum_info = $this->model_catalog_gallimage->getGallalbum($gallalbum['gallimage_id']);

			if ($gallalbum_info) {


				$filter_data = array(
					'start' => 0,
					'limit' => 999999,
					// 'gallimage_id' => $listing_by_category ? $data['cat_id'] : $gallalbum['gallimage_id'],
					'gallimage_id' => $data['cat_id'],
				);

				$results = $this->model_catalog_gallimage->getGallimage($filter_data);
				$gallimages = array();
				if ($results) {
					foreach ($results as $result) {
						if ($result['image']) {
							$thumb = $this->model_tool_image->resize($result['image'], 400, 400);
							//$popupimage = 'image/' . $result['image'];
							$popupimage = $this->model_tool_image->resize($result['image'], 350, 350);
							$popupimage2 = $this->model_tool_image->resize($result['image'], 200, 200);
							$popupimage3 = $this->model_tool_image->resize($result['image'], 600, 600);

						} else {
							$thumb = $this->model_tool_image->resize('placeholder.png', 400, 400);
							$popupimage = 'image/placeholder.png';
							$popupimage2 = 'image/placeholder.png';
						}


						$gallimages[] = array(
							'gallimage_id' => $result['gallimage_id'],
							'title' => $result['title'],
							'description' => $result['description'],
							'link'  => html_entity_decode($result['link'], ENT_QUOTES, 'UTF-8'),
							'thumb' => $thumb,
							'image' => $popupimage,
							'image2' => $popupimage2,
							// 'ori_image'=> 'image/'.$result['image'],
							'ori_image'=> $popupimage3,
						);
					}
				}

				if(is_array($gallimages) && $gallimages) {
					$featured_image = $gallimages[0]['ori_image'];
				}else {
					$featured_image =  $this->model_tool_image->resize('no_image.png', 400, 400);
				}


        /* Pagination */

        $limit = 12;
        $special_total = count($gallimages);
         if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
         } else {
            $page = 1;
         }

         $start = ($page - 1) * $limit;

         $gallimages = array_slice($gallimages, $start, $limit);

        //  debug($gallimages);die;
         $url = '';
         $pagination = new Pagination();
         $pagination->total = $special_total;
         $pagination->page = $page;
         $pagination->limit = $limit;

         $pagination->url = $this->url->link('gallery/album', $url . '&page={page}');

         $data['pagination'] = $pagination->render();

         // load more
         $data['page_steps'] = array();
         $link = '';
         for($i = 1; $i <= ceil($special_total / $limit); $i++){
             $data['page_steps'][] = $this->url->link('gallery/album', $url . '&page=' . $i);
         }
         $data['page_steps'] = html(json_encode($data['page_steps']));
         $data['special_total'] = $special_total;


           /* Pagination */


				$data['gallalbums'][] = array(
					'cat_link' => $cat_link,
					'gallimage_id' => $gallalbum_info['gallimage_id'],
					'name'        => $gallalbum_info['name'],
					'featured_image' => $featured_image,
					'description' => utf8_substr(strip_tags(html_entity_decode($gallalbum_info['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
					'thumb'   	 => $gallalbum_info['image'],
					'href'        => $this->url->link('gallery/gallery', 'gallimage_id=' . $gallalbum_info['gallimage_id']),
					'gallalbum' => $gallimages,
				);
			}
			if($gallalbum_info_current) {
				$data['gallalbums_current'] = array(
					'cat_link' => $cat_link,
					'gallimage_id' => $gallalbum_info_current['gallimage_id'],
					'name'        => $gallalbum_info_current['name'],
					'featured_image' => $featured_image,
					'description' => utf8_substr(strip_tags(html_entity_decode($gallalbum_info_current['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
					'thumb'   	 => $gallalbum_info_current['image'],
					'href'        => $this->url->link('gallery/gallery', 'gallimage_id=' . $gallalbum_info_current['gallimage_id']),
					'gallalbum' => $gallimages,
				);
			}

		}
	}


  // debug($data['gallalbums_current']);die;


    if (version_compare(VERSION, '2.2.0.0', '>=')) {
      // debug($data['gallalbums_current']['gallalbum']);die;
		// $this->response->setOutput($this->load->view('gallery/gallery_'.$gallery_layout , $data));

		$this->response->setOutput($this->load->view('gallery/gallery' , $data));
    } else {
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/gallery/album.tpl')) {
      $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/gallery/album.tpl', $data));
    } else {
      $this->response->setOutput($this->load->view('default/template/gallery/album.tpl', $data));
    }
    }
  }
}
?>
