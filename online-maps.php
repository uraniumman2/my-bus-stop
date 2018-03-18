<?php
    require('config/config.php');
    require('model/model.php');
    $aList = getBusStopList();
    $aBusStopPriority = array();
?>
<?php include('inc/header.php'); ?>
    <div class="container">
        <h3>Выберите остановку</h3>
        <select id="bus_stop" class="form-control">
            <?php foreach($aList AS $aStop) { ?>
                <option value="<?php echo $aStop['bus_stop_id']?>" data-value='<?php echo $aStop["buses"] ?>' data-coord='<?php echo $aStop["coord"] ?>'><?php echo $aStop['caption'] ?></option>
            <?php } ?>
        </select>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div id="map" style="height: 500px;"></div>
            </div>
        </div>
    </div>
<?php include('inc/footer.php'); ?>
<script>
    setTimeout(() => {
        $('#bus_stop').change(function() {
            var iBusId = $(this).val();
            var dataValue = $(this).find('[value="' + iBusId + '"]').attr('data-value');
            var coord = $(this).find('[value="' + iBusId + '"]').attr('data-coord');
            $.ajax({
                type: "POST",
                url: "http://localhost/my-bus-stop/controller/get-map-data.php",
                data: JSON.stringify({ buses: dataValue, current_coord: coord}),
                processData: false,
                async: false,
                contentType: "application/json"
            })
            .done(function (data) {
                // console.log(JSON.parse(coord));
                var stationCoords = JSON.parse(coord);
                initMap(data, stationCoords['Lng'], stationCoords['Ltd']);
            });
        }).change();
    }, 100);
</script>

<script>
    function initMap(data, centerLat, centerLng) {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
          center: (centerLat !== undefined && centerLng !== undefined) ? {lat: centerLat, lng: centerLng} : {lat: 51.13, lng: 71.4},
          mapTypeId: 'roadmap'
        });
        if(data !== undefined) {
            // Marking Current position AS STAR
            image = "src/star.png";
            var beachMarker = new google.maps.Marker({
                position: {lat: centerLat, lng: centerLng},
                map: map,
                icon: image
            });

            var aData = JSON.parse(data);
            var colors = ['red', 'blue', 'green', 'yellow', 'purple', 'orange', 'darkblue', 'pink', 'brown', 'cyan'];
            var colorNumber = 0;
            var strokeWeight = 10;
            for(var item in aData) {
                // console.log(aData[item]);
                var coordinates = JSON.parse(aData[item]['route']);
                console.log(coordinates);
                // var coordinates = [{lat:71.402620153442,lng:51.130208615333},{lat:71.402219441139,lng:51.1302783047},{lat:71.401808,lng:51.130402},{lat:71.401355,lng:51.130579},{lat:71.400918,lng:51.13081},{lat:71.400711,lng:51.130953},{lat:71.400537,lng:51.131088},{lat:71.400293,lng:51.131336},{lat:71.400148,lng:51.13151},{lat:71.399936,lng:51.131879},{lat:71.399861,lng:51.132076},{lat:71.39981,lng:51.132273},{lat:71.399786,lng:51.132454},{lat:71.399791,lng:51.132665},{lat:71.399829,lng:51.132856},{lat:71.399877,lng:51.133018},{lat:71.400026,lng:51.133353},{lat:71.40027,lng:51.133682},{lat:71.400538,lng:51.133942},{lat:71.400895,lng:51.134197},{lat:71.401222,lng:51.134378},{lat:71.401842,lng:51.134626},{lat:71.402089,lng:51.134697},{lat:71.402524,lng:51.134792},{lat:71.403017,lng:51.134859},{lat:71.403258,lng:51.134874},{lat:71.403714,lng:51.134881},{lat:71.404379,lng:51.134829},{lat:71.404379,lng:51.134829},{lat:71.407445,lng:51.134365},{lat:71.407171,lng:51.13367},{lat:71.407171,lng:51.13367},{lat:71.40717,lng:51.133668},{lat:71.405632,lng:51.129737},{lat:71.405632,lng:51.129737},{lat:71.404513,lng:51.126948},{lat:71.406015,lng:51.126715},{lat:71.406015,lng:51.126715},{lat:71.406015,lng:51.126715},{lat:71.406015,lng:51.126715},{lat:71.413083,lng:51.125608},{lat:71.413285,lng:51.126727},{lat:71.413285,lng:51.126727},{lat:71.413285,lng:51.126727},{lat:71.413285,lng:51.126727},{lat:71.413621,lng:51.128494},{lat:71.419244,lng:51.127602},{lat:71.419244,lng:51.127602},{lat:71.419244,lng:51.127602},{lat:71.425402,lng:51.126645},{lat:71.425402,lng:51.126645},{lat:71.425402,lng:51.126644},{lat:71.43254,lng:51.125521},{lat:71.43254,lng:51.125521},{lat:71.432541,lng:51.125521},{lat:71.432541,lng:51.125521},{lat:71.4376,lng:51.124728},{lat:71.437809,lng:51.125267},{lat:71.437809,lng:51.125267},{lat:71.437809,lng:51.125268},{lat:71.439253,lng:51.128965},{lat:71.439646,lng:51.12983},{lat:71.439847,lng:51.130315},{lat:71.439847,lng:51.130315},{lat:71.439848,lng:51.130314},{lat:71.439848,lng:51.130314},{lat:71.439979,lng:51.13063},{lat:71.44019,lng:51.130847},{lat:71.44048,lng:51.131065},{lat:71.440834,lng:51.131232},{lat:71.441808,lng:51.131593},{lat:71.442074,lng:51.131676},{lat:71.442503,lng:51.131752},{lat:71.442739,lng:51.131771},{lat:71.442739,lng:51.131771},{lat:71.443426,lng:51.131751},{lat:71.447851,lng:51.131062},{lat:71.453901,lng:51.130087},{lat:71.453901,lng:51.130087},{lat:71.458065,lng:51.129434},{lat:71.458065,lng:51.129434},{lat:71.458228,lng:51.129408},{lat:71.458228,lng:51.129408},{lat:71.458284,lng:51.129559},{lat:71.459432,lng:51.132471},{lat:71.459598,lng:51.132879},{lat:71.459828,lng:51.133379},{lat:71.459828,lng:51.133379},{lat:71.459829,lng:51.133379},{lat:71.459934,lng:51.133592},{lat:71.460177,lng:51.133951},{lat:71.460448,lng:51.134263},{lat:71.460772,lng:51.134567},{lat:71.460921,lng:51.134679},{lat:71.461275,lng:51.134903},{lat:71.4619,lng:51.135227},{lat:71.462285,lng:51.135393},{lat:71.462717,lng:51.135552},{lat:71.467715,lng:51.13698},{lat:71.467715,lng:51.13698},{lat:71.467715,lng:51.13698},{lat:71.477616,lng:51.139792},{lat:71.477616,lng:51.139792},{lat:71.477616,lng:51.139792},{lat:71.482551,lng:51.141187},{lat:71.482551,lng:51.141187},{lat:71.482551,lng:51.141187},{lat:71.482551,lng:51.141187},{lat:71.486535,lng:51.142314},{lat:71.486866,lng:51.142314},{lat:71.487158,lng:51.142371},{lat:71.487348,lng:51.142378},{lat:71.487509,lng:51.142332},{lat:71.487509,lng:51.142332},{lat:71.487901,lng:51.14209},{lat:71.487955,lng:51.142017},{lat:71.487955,lng:51.142017},{lat:71.487955,lng:51.142017},{lat:71.490124,lng:51.138984},{lat:71.490124,lng:51.138984},{lat:71.490124,lng:51.138984},{lat:71.49148,lng:51.137071},{lat:71.49148,lng:51.137071},{lat:71.49148,lng:51.137071},{lat:71.49148,lng:51.137071},{lat:71.493696,lng:51.133988},{lat:71.494237,lng:51.134142},{lat:71.494237,lng:51.134142},{lat:71.494237,lng:51.134142},{lat:71.502523,lng:51.136484},{lat:71.502523,lng:51.136484},{lat:71.502523,lng:51.136483},{lat:71.508011,lng:51.138056},{lat:71.508011,lng:51.138056},{lat:71.508011,lng:51.138056},{lat:71.511054,lng:51.138929},{lat:71.511054,lng:51.138929},{lat:71.511054,lng:51.138929},{lat:71.511054,lng:51.138929},{lat:71.51383,lng:51.139728},{lat:71.519719,lng:51.137804},{lat:71.519719,lng:51.137804},{lat:71.519719,lng:51.137804},{lat:71.527948,lng:51.135126},{lat:71.527948,lng:51.135126},{lat:71.527948,lng:51.135126},{lat:71.52879,lng:51.134861},{lat:71.529638,lng:51.134621},{lat:71.530158,lng:51.13451},{lat:71.531129,lng:51.134342},{lat:71.531268,lng:51.1343},{lat:71.531268,lng:51.1343},{lat:71.531368,lng:51.134239},{lat:71.531452,lng:51.134112},{lat:71.531469,lng:51.133994},{lat:71.531461,lng:51.133933},{lat:71.531284,lng:51.133492},{lat:71.52938,lng:51.129291},{lat:71.528997,lng:51.12841},{lat:71.528997,lng:51.12841},{lat:71.528997,lng:51.128411},{lat:71.528076,lng:51.126292},{lat:71.527066,lng:51.124012},{lat:71.527007,lng:51.123909},{lat:71.526862,lng:51.123739},{lat:71.526693,lng:51.123614},{lat:71.526615,lng:51.123579},{lat:71.525594,lng:51.123264},{lat:71.525594,lng:51.123264},{lat:71.525593,lng:51.123262},{lat:71.523509,lng:51.12258},{lat:71.523509,lng:51.12258},{lat:71.522301,lng:51.122157},{lat:71.522128,lng:51.12232}];
                // console.log(aData[item]['route']);
                if(coordinates.length) {
                    var busRoute = new google.maps.Polyline({
                        path: coordinates,
                        geodesic: true,
                        strokeColor: colors[(colorNumber++)%(colors.length)],
                        strokeOpacity: 1.0,
                        strokeWeight: strokeWeight--
                    });
                    busRoute.setMap(map);
                }
            }
        }
      }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/staticmap?key=AIzaSyBsxUHtNTPuORMP1eJqieY3rDY71jEoQ4Y&center=51.142686598545595,71.45798567206123&zoom=11&format=png&maptype=roadmap&style=element:geometry%7Ccolor:0xebe3cd&style=element:labels.text.fill%7Ccolor:0x523735&style=element:labels.text.stroke%7Ccolor:0xf5f1e6&style=feature:administrative%7Celement:geometry.stroke%7Ccolor:0xc9b2a6&style=feature:administrative.land_parcel%7Celement:geometry.stroke%7Ccolor:0xdcd2be&style=feature:administrative.land_parcel%7Celement:labels%7Cvisibility:off&style=feature:administrative.land_parcel%7Celement:labels.text.fill%7Ccolor:0xae9e90&style=feature:landscape.natural%7Celement:geometry%7Ccolor:0xdfd2ae&style=feature:poi%7Celement:geometry%7Ccolor:0xdfd2ae&style=feature:poi%7Celement:labels.text.fill%7Ccolor:0x93817c&style=feature:poi.business%7Cvisibility:off&style=feature:poi.park%7Celement:geometry.fill%7Ccolor:0xa5b076&style=feature:poi.park%7Celement:labels.text%7Cvisibility:off&style=feature:poi.park%7Celement:labels.text.fill%7Ccolor:0x447530&style=feature:road%7Celement:geometry%7Ccolor:0xf5f1e6&style=feature:road.arterial%7Celement:geometry%7Ccolor:0xfdfcf8&style=feature:road.highway%7Celement:geometry%7Ccolor:0xf8c967&style=feature:road.highway%7Celement:geometry.stroke%7Ccolor:0xe9bc62&style=feature:road.highway.controlled_access%7Celement:geometry%7Ccolor:0xe98d58&style=feature:road.highway.controlled_access%7Celement:geometry.stroke%7Ccolor:0xdb8555&style=feature:road.local%7Celement:labels%7Cvisibility:off&style=feature:road.local%7Celement:labels.text.fill%7Ccolor:0x806b63&style=feature:transit.line%7Celement:geometry%7Ccolor:0xdfd2ae&style=feature:transit.line%7Celement:labels.text.fill%7Ccolor:0x8f7d77&style=feature:transit.line%7Celement:labels.text.stroke%7Ccolor:0xebe3cd&style=feature:transit.station%7Celement:geometry%7Ccolor:0xdfd2ae&style=feature:water%7Celement:geometry.fill%7Ccolor:0xb9d3c2&style=feature:water%7Celement:labels.text.fill%7Ccolor:0x92998d&size=1200x1200">
</script>