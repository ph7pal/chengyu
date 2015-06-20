var map;
var target_title;
var hasLoadNearBy = false;
var infowindow;
var bounds;
function loadScript() {
    showMapTips("地图正在加载中...");
    var a = document.createElement("script");
    a.type = "text/javascript";
    a.src = googleMapUrl + "/maps/api/js?key=" + apiKey + "&sensor=false&callback=initialize";
    document.body.appendChild(a);
}

function initialize() {
    var b = {
        zoom: thezoom,
        center: new google.maps.LatLng(thelat, thelng),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scaleControl: true,
        scaleControlOptions: {
            position: google.maps.ControlPosition.BOTTOM_LEFT
        },
        draggable: true
    };
    bounds = new google.maps.LatLngBounds();
    map = new google.maps.Map(document.getElementById("map-canvas"), b);
    infowindow = new google.maps.InfoWindow();
    var a = new google.maps.Marker({
        position: map.getCenter(),
        map: map,
        title: originTitle
    });
    google.maps.event.addListener(a, "click", function () {
        infowindow.setContent(contentString);
        infowindow.open(map, this);
    });
    google.maps.event.addListenerOnce(map, "projection_changed", function () {
        $("#panel").css({
            display: "block"
        });
        closeTips();
        listInfo(mapHtml.nearby);
    });
}

function listInfo(data) {
    data = eval( data);
    var len = data.length;
    if (len == 0) {
        showMapTips('暂无周边信息');
        return false
    }
    for (var i = 0; i < len; i++) {
        simpleInfo(data[i]);
        bounds.extend(new google.maps.LatLng(data[i]['lat'], data[i]['long']));
    }    
    bounds.extend(new google.maps.LatLng(thelat, thelng));
    map.fitBounds(bounds);
}
function simpleInfo(info) {
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(info['lat'], info['long']),
        map: map,
        title: info['title']
    });
    var _contentString = info['html'];
    google.maps.event.addListener(marker, 'click', function () {
        infowindow.setContent(_contentString);
        infowindow.open(map, this)
    });
}

function showMapTips(a) {
    if ($("#mapTipsHolder").css("display") == "none") {
        $("#mapTipsHolder").show();
    }
    $("#mapTips").html(a);
    setTimeout("closeTips()", 3e3);
}

function closeTips() {
    $("#mapTips").html('');
    $("#mapTipsHolder").hide();
}

