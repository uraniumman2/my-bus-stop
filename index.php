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
            <?php } ?><!-- Shark 8-->
        </select>
    </div>
    <br>
    <textarea id="result" cols="60" rows="20"></textarea>
<?php include('inc/footer.php'); ?>
<script>
    setTimeout(() => {
        $('#bus_stop').change(function() {
            var iBusId = $(this).val();
            var dataValue = $(this).find('[value="' + iBusId + '"]').attr('data-value');
            var coord = $(this).find('[value="' + iBusId + '"]').attr('data-coord');
            var caption = $(this).find(':selected').text();
            $.ajax({
                type: "POST",
                url: "http://localhost/my-bus-stop/controller/get-converted-data.php",
                data: JSON.stringify({ buses: dataValue, current_coord: coord, caption: caption}),
                processData: false,
                async: false,
                contentType: "application/json"
            })
            .done(function (data) {
                // console.log(JSON.parse(coord));
                // var stationCoords = JSON.parse(coord);
                $('#result').val(data);
            });
        }).change();
    }, 100);
</script>
