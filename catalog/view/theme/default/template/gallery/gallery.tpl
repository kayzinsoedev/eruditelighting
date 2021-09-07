<?php echo $header; ?>
<div class="container">
  <div class="white_background">
      <?php echo $content_top; ?>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <!-- <div id="content" class="gallery_<?=$gallery_layout;?> <?php echo $class; ?>"> -->
        <div id="content" class="<?php echo $class; ?>">
          <!-- <?php echo $content_top; ?>
          <?php echo $content_bottom; ?> -->
          <h2><?php echo $heading_title; ?></h2>
            <div class="gallery-content">
              <?php if(isset($gallalbums) && $gallalbums) { ?>
                <ul class="nav custom-tabs pd-b30 gallery-cat flex-wrap flex flex-hcenter flex-vcenter gallery-tab">
                  <?php $tab_count = 0;?>
                  <?php foreach($gallalbums as $cat) { ?>
                    <li class="gallery-cat <?=$cat['gallimage_id'] == $cat_id ? 'active': ''?>">
                      <a href="<?=$cat['cat_link'];?>" class="text-uppercase btn-primary cat <?=$cat['gallimage_id'] == $cat_id ? 'active': ''?>"><?=$cat['name'];?></a>
                    </li>
                   <?php } ?>
                </ul>

                <div id="newsResults<?= $cat_id ?>"  class="galleries flex gutter-row four-col-row" style="flex-wrap:wrap;">

                    <?php foreach($gallalbums_current['gallalbum'] as $album) {  ?>
                      <div class="gutter slider_solution_box">
                        <!-- <div class="gallery posrel transition" data-toggle="modal" data-target="#GalleryPopup" style="background:url('<?=$album['image'];?>'); background-color: #fff;"> -->
                        <div class="gallery posrel transition" data-toggle="modal" data-target="#GalleryPopup" >

                          <img src="<?=$album['image'];?>" class="gall-img">
                          <!-- <div class="gall-img" style="background-image:url('<?=$album['image'];?>')"> </div> -->
                          <div class="overlay">
                              <div class="library-text">
                                <img src="image/catalog/Project/view.png" alt="view" class="gall-view-img img-responsive">
                                <div class="gall-title"><?=$album['title'];?></div>
                                <!-- <div class="gall-desc"><?=$album['description'];?></div> -->
                              </div>
                          </div>

                        </div>
                      </div>

                     <?php } ?>
                </div>

                <!-- <div class="gallery-load-more"><span class="load-more-btn">Load More</span></div> -->
                <div class="load-more-pagi"></div>
                <!-- <button type="button" id="loadMore" data-loading-text="Loading" class="btn btn-primary">View More</button> -->
                <div class="row pb-80 relative pagi-button">
	                   <div class="col-sm-12 text-center"><button type="button" id="loadMore" data-loading-text="Loading" class="btn btn-primary">Load More</button></div>
	              </div>


                  <!-- Modal -->
                <div class="modal fade" id="GalleryPopup" role="dialog">
                  <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header hidden">
                          <button type="button" class="close" data-dismiss="modal" title="Close">
                            <img src="image/catalog/Project/close.png" alt="close" class="img-responsive">
                          </button>
                      </div>
                      <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" title="Close">
                          <img src="image/catalog/Project/close.png" alt="close" class="img-responsive">
                        </button>


                        <div class="popup-gallery image">
                          <div class="main pd-b20">
                            <?php foreach($gallalbums_current['gallalbum'] as $gall) {?>
                              <img src="<?=$gall['ori_image'];?>" class="img-responsive"/>
                              <!-- <div class="gall-pop-up-img" style="background-image:url('<?=$gall['ori_image'];?>')"></div> -->
                            <?php } ?>
                          </div>
                          <!-- <div> -->
                          <?php $gallery_count = count($gallalbums_current['gallalbum']); ?>
                          <div class="gallery-title text-center text-uppercase">
                              <span class="gallery-name"><?=$album['title'];?></span>
                              <!-- <span class="gallery-count"><?=count($gallalbums_current['gallalbum']);?></span> -->
                              <span class="gallery-count"></span>
                          </div>

                          <!-- </div> -->
                          <div class="thumb hidden">
                            <?php foreach($gallalbums_current['gallalbum'] as $gall) { ?>
                              <img src="<?=$gall['ori_image'];?>" class="img-responsive"/>
                            <?php } ?>
                          </div>
                      </div>
                      </div>
                    </div>

                  </div>
                </div>


              <?php } else { ?>
                <div class="flex flex-vcenter flex-hcenter">
                  No Gallery Found
                </div>
              <?php } ?>

            </div>

          <script>


              /*pagination */

              var current = 1;
            	var thread = null;
            	var pg_list = jQuery.parseJSON('<?= $page_steps ?>');

              var cat_d = '<?= $cat_id ?>';

              if(current in pg_list == false) {
                      $('#loadMore').remove();
                      $(".load-more-pagi").remove();
                  }

              // console.log(cat_d);

              $(document).on('click', '#loadMore', function(e){
                    e.preventDefault();
                    btn = $(this);
                    $('#loadMore').addClass('disabled').prop('disabled', true);
                    $('#loadMore').button('loading');
                    if(thread) thread.abort();

                     thread = $.get(pg_list[current] + '&cat_id=' + cat_d, function(html){
                      let articles = $(html).find('#newsResults' + cat_d).html();

                      console.log(html);
                      console.log('#newsResults' + cat_d);
                      // load more item no need use lazy load for images
                      $('#newsResults' + cat_d).append(articles);

                      current++;
                      $('#loadMore').removeClass('disabled').prop('disabled', false).button('reset');
                      if(current in pg_list == false) {
                              $('#loadMore').remove();
                              $(".load-more-pagi").remove();
                          }
                    });
              });



              // $('#loadMore').addClass('disabled').prop('disabled', true);
              // $('#loadMore').button('loading');
              // if(thread) thread.abort();
              //
              //
              // thread = $.get(pg_list[current], function(html){
            	// 	  //let results = $(html).find('#paginationResults').text();
            	// 	  //$('#paginationResults').text(results);
            	// 	  let articles = $(html).find('#newsResults').html();
            	// 	  // load more item no need use lazy load for images
            	// 	  $('#newsResults').append(articles);
              //
            	// 	  current++;
            	// 	  $('#loadMore').removeClass('disabled').prop('disabled', false).button('reset');
            	// 	  if(current in pg_list == false) $('#loadMore').remove();
              //
            	// 	  if(current in pg_list == false) {
            	// 		  $('#loadMore').remove();
            	// 		  $(".load-more-pagi").remove();
            	// 	  }
        		// });

            /*pagination */




              $('#GalleryPopup').on('shown.bs.modal', function () {
                  $('.popup-gallery .main').slick('refresh');
                  $('.popup-gallery .thumb').slick('refresh');
              });

              jQuery(document).ready(function ($) {
                $('.popup-gallery .main').slick({
                      slidesToShow: 1,
                      // lazyLoad: 'ondemand',
                      slidesToScroll: 1,
                      arrows: true,
                      fade: true,
                      adaptiveHeight: true,
                      infinite: false,
                      asNavFor: '.popup-gallery .thumb',
                      prevArrow: "<div class='pointer slick-nav left prev'>
                      <img src='image/catalog/Project/left1.png' alt='left-arrow' class='left-arrow'>
                      </div>",
                      nextArrow: "<div class='pointer slick-nav right next'>
                      <img src='image/catalog/Project/right1.png' alt='right-arrow' class='right-arrow'>
                      </div>",
                  });


                    var gallery_count = '<?php echo json_encode($gallery_count);?>';


                    var $status = $('.gallery-count');
                    var $slickElementTat = $('.popup-gallery .slick-slider');

                    var img_ctr = gallery_count;

                    $status.text(1 + '  /  ' + '0'+img_ctr);
                    $slickElementTat.on('afterChange', function (event, slick, currentSlide, nextSlide) {
                        //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
                        currentSlide = slick.slickCurrentSlide();
                        console.log(currentSlide);
                        var i = (currentSlide ? currentSlide : 0) + 1;
                        Tcurrent = i;
                        $status.text(i + '  /  ' + img_ctr);

                    });


                  $('.popup-gallery .thumb').slick({
                      slidesToShow: 4,
                      // lazyLoad: 'ondemand',
                      slidesToScroll: 1,
                      asNavFor: '.popup-gallery .main',
                      dots: false,
                      centerMode: false,
                      arrows:false,
                      focusOnSelect: true,
                      infinite: false,
                      prevArrow: "<div class='pointer slick-nav left prev'><i class='fa fa-angle-left'></div>",
                      nextArrow: "<div class='pointer slick-nav right next'><i class='fa fa-angle-right'></div>",
                      responsive: [
                      {
                          breakpoint: 767,
                          settings: {
                              slidesToShow: 3,
                              slidesToScroll: 5,
                          }
                      },
                      ]
                  });
              });
          </script>
        </div>
        <?php echo $column_right; ?>


      </div>
  </div>
</div>
<?php echo $footer; ?>
