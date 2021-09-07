
<h2>Brands</h2>
<div class="brands-container">
  <?php foreach($row_contents as $row_content){ ?>
  <div class="brands-content">
        <a href="<?=$row_content['link'];?>">
          <img src="image/<?=$row_content['image'];?>" alt="brand" class="img-responsive">
          <div class="brand-name"><?=$row_content['name'];?></div>
        </a>
  </div>

      <!-- <div class="col-md-4">
              <a href="<?=$row_content['link'];?>">
              <img src="image/<?=$row_content['image'];?>" alt="brand" class="img-responsive">
              <div class="brand-name"><?=$row_content['name'];?></div>
              </a>
      </div> -->

  <?php  } ?>

</div>
