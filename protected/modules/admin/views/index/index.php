<?php $this->renderPartial('_nav');?>
<table class="table table-hover">	
<tr><td>服务器软件：</td><td><?php echo $siteinfo['serverOS']; ?>-<?php echo $siteinfo['serverSoft']; ?>  PHP-<?php echo $siteinfo['PHPVersion']; ?></td></tr>
<tr><td>数据库版本：</td><td><?php echo $siteinfo['mysqlVersion'];?>（<?php echo $siteinfo['dbsize'];?>）</td></tr>
<tr><td>上传许可：</td><td><?php echo $siteinfo['fileupload'];?></td></tr>
<tr><td>主机名：</td><td><?php echo $siteinfo['serverUri'];?></td></tr>
<tr><td>最大执行时间：</td><td><?php echo $siteinfo['maxExcuteTime'];?></td></tr>
<tr><td>最大执行内存：</td><td><?php echo $siteinfo['maxExcuteMemory'];?></td></tr>
<tr><td>当前使用内存：</td><td><?php echo $siteinfo['excuteUseMemory'];?></td></tr>
</table>