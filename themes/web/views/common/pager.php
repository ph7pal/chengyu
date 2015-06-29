<?php if($pages->pageCount>1){?>
<div class="pagination">
 <?php 
 $this->widget('CLinkPager',
         array(
            'header'=>'',
             'firstPageLabel' => zmf::t('firstPage'),
             'lastPageLabel' => zmf::t('lastPage'),    
             'prevPageLabel' => zmf::t('prevPage'),    
             'nextPageLabel' => zmf::t('nextPage'),    
             'pages' => $pages,    
             'maxButtonCount'=>4 
         )         
         );
 ?>
</div>  
<?php }?>