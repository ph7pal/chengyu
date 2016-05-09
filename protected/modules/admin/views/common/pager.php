<div class="clearfix"></div>
<div style="margin-top: 15px;" class="visible-xs">
 <?php 
 //分页widget代码: 
 $this->widget('CLinkPager',
         array(
            'header'=>'',
             'firstPageLabel' => '首页',
             'lastPageLabel' => '末页',    
             'prevPageLabel' => '上一页',    
             'nextPageLabel' => '下一页',    
             'pages' => $pages,    
             'maxButtonCount'=>0  
         )         
         );
 ?>
</div>
<div style="margin-top: 15px;" class="hidden-xs">
    <div style="float: left;padding-right: 15px;">
        共<?php echo $pages->itemCount.'条';?>
    </div>
    <div style="float: left">
         <?php 
            //分页widget代码: 
            $this->widget('CLinkPager',
                    array(
                       'header'=>'',
                        'firstPageLabel' => '首页',
                        'lastPageLabel' => '末页',    
                        'prevPageLabel' => '上一页',    
                        'nextPageLabel' => '下一页',    
                        'pages' => $pages,    
                        'maxButtonCount'=>10
                    )         
                    );
            ?>
    </div>    
</div>