<div class="map-holder">
    <script>
        var map;
        var geocoder;
        var current_lat = '29.55942';
        var current_lng = '106.58334';
        var current_zoom =13;
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
                $("#lat").val(map.getCenter().lat());
                $("#long").val(map.getCenter().lng());
                $("#mapZoom").val(map.getZoom());                
            });
            google.maps.event.addListener(map, 'zoom_changed', function () {
                $("#mapZoom").val(map.getZoom());
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
                    $("#lat").val(_marker.getPosition().lat());
                    $("#long").val(_marker.getPosition().lng());
                    $("#mapZoom").val(map.getZoom());                    
                });
                google.maps.event.addListener(_marker, 'dragend', function () {
                    map.panTo(_marker.getPosition());
                    $("#currentinfo").html(_marker.getPosition().lat() + ',' + _marker.getPosition().lng());
                    $("#lat").val(_marker.getPosition().lat());
                    $("#long").val(_marker.getPosition().lng());
                    $("#mapZoom").val(map.getZoom());                    
                });
            });
            $("#lat").val(map.getCenter().lat());
            $("#long").val(map.getCenter().lng());            
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
                $("#lat").val(marker.getPosition().lat());
                $("#long").val(marker.getPosition().lng());
                $("#mapZoom").val(map.getZoom());                
            });

            google.maps.event.addListener(marker, 'dragend', function () {
                map.panTo(marker.getPosition());                
                $("#lat").val(marker.getPosition().lat());
                $("#long").val(marker.getPosition().lng());
                $("#mapZoom").val(map.getZoom());                
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
                $("#lat").val(map.getCenter().lat());
                $("#long").val(map.getCenter().lng());
                $("#mapZoom").val(map.getZoom());                
            });
            google.maps.event.addListener(map, 'zoom_changed', function () {
                $("#mapZoom").val(map.getZoom());
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
                    $("#lat").val(_marker.getPosition().lat());
                    $("#long").val(_marker.getPosition().lng());
                    $("#mapZoom").val(map.getZoom());
                });

                google.maps.event.addListener(_marker, 'dragend', function () {
                    map.panTo(_marker.getPosition());                    
                    $("#lat").val(_marker.getPosition().lat());
                    $("#long").val(_marker.getPosition().lng());
                    $("#mapZoom").val(map.getZoom());
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
    <div id="map-canvas" style="width:100%;height:480px;"></div>    
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">经度</span>
            <input type="text" id="long" class="form-control" placeholder="标注点的经度">        
        </div>
        <div class="input-group">
            <span class="input-group-addon">纬度</span>
            <input type="text" id="lat" class="form-control" placeholder="标注点的纬度">        
        </div>    
        <div class="input-group">
            <span class="input-group-addon">缩放</span>
            <input type="text" id="mapZoom" class="form-control" placeholder="当前地图缩放级别">
        </div>
    </div>
    <div class="pull-right">
        <div class="btn-group" role="group">
            <button type="button"  id="addMapImgBtn" class="btn btn-danger">插入当前地图</button>
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
        $("#addMapImgBtn").click(function () {
            var lat=$('#lat').val();
            var long=$('#long').val();
            var zoom=$('#mapZoom').val();
            if(!lat || !long){
                dialog({msg:'请先定位坐标'});
                return false;
            }
            zoom=zoom?zoom:13;
            var img='<p><img src="http://ditu.google.cn/maps/api/staticmap?center='+lat+','+long+'&amp;zoom='+zoom+'&amp;size=640x480&amp;markers=color:red%7Clabel:A%7C'+lat+','+long+'&amp;sensor=false&amp;key=<?php echo zmf::config('googleApiKey');?>" style="width:100%" mapInfo="'+lat+'#'+long+'#'+zoom+'"/></p>';
            myeditor.execCommand("inserthtml", img);
        });        
    </script>
</div>