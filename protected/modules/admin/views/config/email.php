<?php echo CHtml::hiddenField('type',$type);?>
<p><label>SMTP HOST：</label><input class="form-control" name="email_host" id="email_host" value="<?php echo $c['email_host'];?>"/></p>
<p><label>发件人名称：</label><input class="form-control" name="email_fromname" id="email_fromname" value="<?php echo $c['email_fromname'];?>"/></p>
<p><label>SMTP用户名：</label><input class="form-control" name="email_username" id="email_username" value="<?php echo $c['email_username'];?>"/></p>
<p><label>SMTP密码：</label><input class="form-control" name="email_password" id="email_password" value="<?php echo $c['email_password'];?>" type="password"/></p>
<p><label>SMTP端口：</label><input class="form-control" name="email_port" id="email_port" value="<?php echo $c['email_port'];?>"/></p>
<p><label>邮件编码：</label><input class="form-control" name="email_chartset" id="email_chartset" value="<?php echo $c['email_chartset'];?>"/></p>
<p><label>接收回复邮件地址：</label><input class="form-control" name="email_replyto" id="email_replyto" value="<?php echo $c['email_replyto'];?>"/></p>
<p><label>接收回复邮件人名称：</label><input class="form-control" name="email_replyname" id="email_replyname" value="<?php echo $c['email_replyname'];?>"/></p>
