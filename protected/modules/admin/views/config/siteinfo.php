<?php echo CHtml::hiddenField('type',$type);?>
<p><label>地址：</label><input class="form-control" name="address" id="address" value="<?php echo $c['address'];?>"/></p>
<p><label>电话：</label><input class="form-control" name="phone" id="phone" value="<?php echo $c['phone'];?>"/></p>	
<p><label>传真：</label><input class="form-control" name="fax" id="fax" value="<?php echo $c['fax'];?>"/></p>
<p><label>邮箱：</label><input class="form-control" name="email" id="email" value="<?php echo $c['email'];?>"/></p>
<p><label>备案：</label><input class="form-control" name="beian" id="beian" value="<?php echo $c['beian'];?>"/></p>
<p><label>版权：</label><input class="form-control" name="copyright" id="copyright" value="<?php echo $c['copyright'];?>"/></p>
<p><label>统计：</label><textarea class="form-control" name="tongji" id="tongji"><?php echo $c['tongji'];?></textarea></p>