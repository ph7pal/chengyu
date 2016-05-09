<?php echo CHtml::hiddenField('type',$type);?>
<p><label>应用环境：</label>
    <select name="appStatus" id="appStatus">
        <option value="1" <?php if($c['appStatus']=='1'){?>selected="selected"<?php }?>>本地开发</option>
        <option value="2" <?php if($c['appStatus']=='2'){?>selected="selected"<?php }?>>线上测试</option>
        <option value="3" <?php if($c['appStatus']=='3'){?>selected="selected"<?php }?>>线上正式</option>
    </select>
</p>
<p><label>开启手机网页版：</label>
    <select name="mobile" id="mobile">
        <option value="0" <?php if($c['mobile']=='0'){?>selected="selected"<?php }?>>关闭</option>
        <option value="1" <?php if($c['mobile']=='1'){?>selected="selected"<?php }?>>开启</option>
    </select>
</p>
<p><label>微信AK：</label><input class="form-control" type="text" name="weixin_app_id" id="weixin_app_id" value="<?php echo $c['weixin_app_id'];?>"/></p>
<p><label>微信SK：</label><input class="form-control" type="text" name="weixin_app_key" id="weixin_app_key" value="<?php echo $c['weixin_app_key'];?>"/></p>
<p><label>微信回调地址：</label><input class="form-control" type="text" name="weixin_app_callback" id="weixin_app_callback" value="<?php echo $c['weixin_app_callback'];?>"/></p>
<p><label>微博网站接入APPKEY：</label><input class="form-control" type="text" name="weixin_app_id" id="weiboAppkey" value="<?php echo $c['weiboAppkey'];?>"/></p>
<p><label>官方微博ID：</label><input class="form-control" type="text" name="weiboRalateUid" id="weiboRalateUid" value="<?php echo $c['weiboRalateUid'];?>"/></p>
<p><label>谷歌地图API KEY：</label><input class="form-control" name="googleApiKey" id="googleApiKey" value="<?php echo $c['googleApiKey'];?>"/></p>    
<p><label>地图默认缩放比例：</label><input class="form-control" name="mapZoomLevel" id="mapZoomLevel" value="<?php echo $c['mapZoomLevel'];?>"/></p>
<p><label>地图图片宽度：</label><input class="form-control" name="mapImgWidth" id="mapImgWidth" value="<?php echo $c['mapImgWidth'];?>"/></p>
<p><label>地图图片高度：</label><input class="form-control" name="mapImgHeight" id="mapImgHeight" value="<?php echo $c['mapImgHeight'];?>"/></p>