<?php
    class ControllerComponentProductInfo extends Controller{
        public function index($product_id = 0){

            $url = '';
            
            if( is_array($product_id) ){
                $url = $product_id['url']; 
                $product_id = $product_id['product_id']; 
            }

            $this->load->language('component/product_info');
            // Clean Value
            $product_id = (int)$product_id;

            // No product id pass into this controller
            if(!$product_id) return '';

            $this->load->model('catalog/product');

            $product_info = $this->model_catalog_product->getProduct($product_id);

            // Product Disabled / Deleted
            if(!$product_info) return '';

            $theme = $this->config->get('config_theme');
            $width = $this->config->get($theme . '_image_product_width');
            $height = $this->config->get($theme . '_image_product_height');

            $image = $this->model_tool_image->resize('no_image.png', $width, $height);
                
            $price = false;
            $special = false;
            $rating = false;
            $tax = false;

            if (is_file(DIR_IMAGE . $product_info['image']) && $product_info['image']) 
                $image = $this->model_tool_image->resize($product_info['image'], $width, $height);
            
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price'))
                $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            
            if ((float)$product_info['special'])
                $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            
            if ($this->config->get('config_tax'))
                $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
            
            if ($this->config->get('config_review_status'))
                $rating = (int)$product_info['rating'];

            $options = $this->model_catalog_product->getProductOptions($product_id);

            $product_url = $this->url->link('product/product', 'product_id=' . $product_info['product_id']);

            if ($url) {
                $product_url = $this->url->link('product/product', $url . '&product_id=' . $product_info['product_id']);
            }

            $sticker = $this->load->controller('component/sticker', $product_info['product_id']);

            $order_link = $this->url->link('information/contact','&product_id=' . $product_info['product_id']);
            
            $info = array(
                'product_id'        => $product_info['product_id'],
				'thumb'             => $image, 
                'name'              => $product_info['name'],
                'minimum'           => $product_info['minimum'],
				'description'       => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($theme . '_product_description_length')),
				'price'             => $price,
				'special'           => $special,
				'tax'               => $tax,
				'rating'            => $rating,
                'href'              => $product_url,
                
                // Text
				'text_tax'			=> $this->language->get('text_tax'),
				'button_cart'		=> $this->language->get('button_cart'),
				'button_wishlist'	=> $this->language->get('button_wishlist'),
                'button_compare'	=> $this->language->get('button_compare'),
                'button_enquiry'    => $this->language->get('button_enquiry'),
                'label_enquiry'     => $this->language->get('label_enquiry'),
                'text_add_cart'     => $this->language->get('text_add_cart'),

                'more_options'      => $options?$this->language->get('text_more_options_available'):'',
                
                'enquiry'           => ((float)$product_info['price'] <= 0),

                'sticker'           =>  $sticker,
                'out_of_stock'      =>  $this->config->get('config_stock_checkout')?'':($product_info['quantity']>0?'':'out-of-stock'),
                'order_link'        =>  $order_link,
            );

            return $this->load->view('component/product_info', $info);
        }
    }