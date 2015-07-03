<?php if($pages->pageCount>1){?>
<div class="pagination">
 <?php 
 $this->widget('CLinkPager',
         array(
            'header'=>'',
             'firstPageLabel' => '',
             'lastPageLabel' => '',    
             'prevPageLabel' => zmf::t('prevPage'),    
             'nextPageLabel' => zmf::t('nextPage'),    
             'pages' => $pages,    
             'maxButtonCount'=>0
         )         
         );
 ?>
</div>  
<?php }?>