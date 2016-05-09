<div class="form-group">
    <p class="help-block add-map-tips" onclick=" $('.map-holder').slideDown();loadScript()"><i class="fa fa-map-marker"></i> 点击添加坐标信息<i class="fa fa-angle-double-down"></i></p>
</div>
<div class="map-holder">
    <script>
        var map;
        var geocoder;
        var current_lat = '<?php echo $model->lat!='' ? $model->lat : '29.55942';?>';
        var current_lng = '<?php echo $model->long!='' ? $model->long : '106.58334';?>';
        var current_zoom = <?php echo $model->mapZoom>0 ? $model->mapZoom : '13';?>;
        var markers = [];
        var initMap = false;
        function initialize() {
            initMap = true;
            geocoder = new google.maps.Geocoder();
            var mapOptions = {
                zoom: current_zoom,
                center: new google.maps.LatLng(current_lat, current_lng),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                draggable: true
            };
            var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            myAction(map, map.getCenter());
            google.maps.event.addListener(map, 'center_changed', function () {
                map.panTo(map.getCenter());
                $("#<?php echo CHtml::activeId($model,'lat');?>").val(map.getCenter().lat());
                $("#<?php echo CHtml::activeId($model,'long');?>").val(map.getCenter().lng());
                $("#<?php echo CHtml::activeId($model,'mapZoom');?>").val(map.getZoom());                
            });
            google.maps.event.addListener(map, 'zoom_changed', function () {
                $("#<?php echo CHtml::activeId($model,'mapZoom');?>").val(map.getZoom());
            });
            google.maps.event.addListener(map, 'dragend', function () {
                var title = '按住左键可拖动';
                var _marker = new google.maps.Marker({
                    position: map.getCenter(),
                    map: map,
                    title: title,
                    draggable: true
                });
                map.panTo(map.getCenter());
                clearMarkers();
                markers.push(_marker);
                addInfo(map, _marker, '');
                google.maps.event.addListener(_marker, 'click', function () {
                    addInfo(map, _marker, title);
                    $("#currentinfo").html(_marker.getPosition().lat() + ',' + _marker.getPosition().lng());
                    $("#<?php echo CHtml::activeId($model,'lat');?>").val(_marker.getPosition().lat());
                    $("#<?php echo CHtml::activeId($model,'long');?>").val(_marker.getPosition().lng());
                    $("#<?php echo CHtml::activeId($model,'mapZoom');?>").val(map.getZoom());                    
                });
                google.maps.event.addListener(_marker, 'dragend', function () {
                    map.panTo(_marker.getPosition());
                    $("#currentinfo").html(_marker.getPosition().lat() + ',' + _marker.getPosition().lng());
                    $("#<?php echo CHtml::activeId($model,'lat');?>").val(_marker.getPosition().lat());
                    $("#<?php echo CHtml::activeId($model,'long');?>").val(_marker.getPosition().lng());
                    $("#<?php echo CHtml::activeId($model,'mapZoom');?>").val(map.getZoom());                    
                });
            });
            $("#<?php echo CHtml::activeId($model,'lat');?>").val(map.getCenter().lat());
            $("#<?php echo CHtml::activeId($model,'long');?>").val(map.getCenter().lng());            
        }
        function myAction(map, center, title) {
            var marker = new google.maps.Marker({
                position: center,
                map: map,
                title: title,
                draggable: true
            });
            markers.push(marker);
            map.panTo(center);
            google.maps.event.addListener(marker, 'click', function () {
                addInfo(map, marker, title);                
                $("#<?php echo CHtml::activeId($model,'lat');?>").val(marker.getPosition().lat());
                $("#<?php echo CHtml::activeId($model,'long');?>").val(marker.getPosition().lng());
                $("#<?php echo CHtml::activeId($model,'mapZoom');?>").val(map.getZoom());                
            });

            google.maps.event.addListener(marker, 'dragend', function () {
                map.panTo(marker.getPosition());                
                $("#<?php echo CHtml::activeId($model,'lat');?>").val(marker.getPosition().lat());
                $("#<?php echo CHtml::activeId($model,'long');?>").val(marker.getPosition().lng());
                $("#<?php echo CHtml::activeId($model,'mapZoom');?>").val(map.getZoom());                
            });
        }
        function addInfo(map, marker, title) {
            var contentString = title;
            var infowindow = new google.maps.InfoWindow({
                content: contentString,
                title: '按住左键可拖动'
            });
            infowindow.open(map, marker);
        }

        function codeAddress() {
            var mapOptions = {
                zoom: 13,
                //center: new google.maps.LatLng(29.55942, 106.58334),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                draggable: true
            };
            var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            var address = document.getElementById('address').value;
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    for (var i = 0; i < results.length; i++) {
                        myAction(map, results[i].geometry.location, results[i].formatted_address);
                    }                    
                } else {
                    alert('由于以下原因，定位未能成功： ' + status);
                    return false;
                }
            });
            google.maps.event.addListener(map, 'center_changed', function () {
                map.panTo(map.getCenter());
                $("#<?php echo CHtml::activeId($model,'lat');?>").val(map.getCenter().lat());
                $("#<?php echo CHtml::activeId($model,'long');?>").val(map.getCenter().lng());
                $("#<?php echo CHtml::activeId($model,'mapZoom');?>").val(map.getZoom());                
            });
            google.maps.event.addListener(map, 'zoom_changed', function () {
                $("#<?php echo CHtml::activeId($model,'mapZoom');?>").val(map.getZoom());
            });
            google.maps.event.addListener(map, 'dragend', function () {
                var title = '按住左键可拖动';
                var _marker = new google.maps.Marker({
                    position: map.getCenter(),
                    map: map,
                    title: title,
                    draggable: true
                });
                map.panTo(map.getCenter());
                clearMarkers();
                markers.push(_marker);
                addInfo(map, _marker, '');

                google.maps.event.addListener(_marker, 'click', function () {
                    addInfo(map, _marker, title);                    
                    $("#<?php echo CHtml::activeId($model,'lat');?>").val(_marker.getPosition().lat());
                    $("#<?php echo CHtml::activeId($model,'long');?>").val(_marker.getPosition().lng());
                    $("#<?php echo CHtml::activeId($model,'mapZoom');?>").val(map.getZoom());
                });

                google.maps.event.addListener(_marker, 'dragend', function () {
                    map.panTo(_marker.getPosition());                    
                    $("#<?php echo CHtml::activeId($model,'lat');?>").val(_marker.getPosition().lat());
                    $("#<?php echo CHtml::activeId($model,'long');?>").val(_marker.getPosition().lng());
                    $("#<?php echo CHtml::activeId($model,'mapZoom');?>").val(map.getZoom());
                });
            });
        }
        
        function clearMarkers() {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
        }

        function loadScript() {
            if (!initMap) {
                var script = document.createElement("script");
                script.type = "text/javascript";
                script.src =  "http://ditu.google.cn/maps/api/js?key=<?php echo zmf::config('googleApiKey');?>&sensor=false&callback=initialize";
                document.body.appendChild(script);
            }
        }
    </script>
    <div class="input-group">
        <input id="address" type="text" placeholder="重庆" class="form-control">
        <span class="input-group-btn"><input id="btnaddress" type="button" value="定位" class="btn btn-primary" onclick="codeAddress()"></span>
    </div>    
    <div id="map-canvas" style="width:618px;height:480px;"></div>    
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">经度</span>
            <input type="text" value="<?php echo $model->long;?>" id="<?php echo CHtml::activeId($model,'long');?>"  name="<?php echo CHtml::activeName($model,'long');?>" class="form-control" placeholder="标注点的经度">        
        </div>
        <div class="input-group">
            <span class="input-group-addon">纬度</span>
            <input type="text" value="<?php echo $model->lat;?>" id="<?php echo CHtml::activeId($model,'lat');?>"  name="<?php echo CHtml::activeName($model,'lat');?>" class="form-control" placeholder="标注点的纬度">        
        </div>    
        <div class="input-group">
            <span class="input-group-addon">缩放</span>
            <input type="text" value="<?php echo $model->mapZoom;?>" id="<?php echo CHtml::activeId($model,'mapZoom');?>"  name="<?php echo CHtml::activeName($model,'mapZoom');?>" class="form-control" placeholder="当前地图缩放级别">
        </div>
    </div>
    <div class="pull-right">
        <div class="btn-group" role="group">
            <button type="button"  id="cancelmapbutton" class="btn btn-default">删除定位信息</button>
            <button type="button" class="btn btn-primary" onclick="$('.map-holder').slideUp();"><i class="fa fa-angle-double-up"></i></button>
        </div>
    </div>
    <div class="clearfix"></div>
    <script>
        $("#address").keypress(function (event) {
            var e = event || window.event;
            if ((e.keyCode == 13 || e.which == 13) && this.value != "") {
                $('#btnaddress').click();
                return false;
            }
        });
        $("#cancelmapbutton").click(function () {
            $('#<?php echo CHtml::activeId($model,'lat');?>').val('');
            $('#<?php echo CHtml::activeId($model,'long');?>').val('');
            $('#<?php echo CHtml::activeId($model,'mapZoom');?>').val('');
            current_lat = 0;
            current_lng = 0;
            return false;
        });        
    </script>
</div>