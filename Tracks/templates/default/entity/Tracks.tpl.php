<?php

$object = $vars['object'];

$metric = \Idno\Core\Idno::site()->config()->tracks['metric'];
if ($metric){
    $dist = 'km';
    $ele = 'm';
} else {
    $dist = 'mi';
    $ele = 'ft';
}

if (\Idno\Core\Idno::site()->currentPage()->isPermalink()) {
    $rel = 'rel="in-reply-to"';
} else {
    $rel = '';
}
?>
<h2 class="p-name"><a href="<?= $object->getURL(); ?>"><?= htmlentities(strip_tags($object->getTitle()), ENT_QUOTES, 'UTF-8'); ?></a></h2>

<?php
if ($attachments = $object->getAttachments()) {
    foreach ($attachments as $attachment) {
        $mainsrc = $attachment['url'];
//        remarque : $attachment['mime-type'] ----> sapplication/octet-stream 
        ?>

        <section id="map<?= $attachment['_id'] ?>" class="gpx" data-gpx-source="<?= $this->makeDisplayURL($mainsrc) ?>" data-map-target="<?= $attachment['_id'] ?>">
            <header>
                <h3>Loading...</h3>
                <span class="start"></span>
            </header>

            <article>
                <div class="map" id="<?= $attachment['_id'] ?>"></div>
            </article>

            <footer>
                <ul class="info">
                    <li id="dist<?= $attachment['_id'] ?>">Distance:&nbsp;<span class="distance"></span>&nbsp;<?=$dist;?> </li>
                    <li id="duration<?= $attachment['_id'] ?>">&mdash; Duration:&nbsp;<span class="duration"></span></li>
                    <li id="pace<?= $attachment['_id'] ?>">&mdash; Pace:&nbsp;<span class="pace"></span>/<?=$dist;?></li>
                    <li id="hr<?= $attachment['_id'] ?>">&mdash; Avg&nbsp;HR:&nbsp;<span class="avghr"></span>&nbsp;bpm</li>
                    <li id="ele<?= $attachment['_id'] ?>">&mdash; Elevation:&nbsp;+<span class="elevation-gain"></span>&nbsp;<?=$ele?>,
                        -<span class="elevation-loss"></span>&nbsp;<?=$ele?>
                        (net:&nbsp;<span class="elevation-net"></span>&nbsp;<?=$ele?>)</li>
                </ul>
            </footer>
        </section>
<script type="application/javascript">
      function display_gpx<?= $attachment['_id'] ?>(elt) {
        if (!elt) return;

        var url = elt.getAttribute('data-gpx-source');
        var mapid = elt.getAttribute('data-map-target');
        if (!url || !mapid) return;

        function _t(t) { return elt.getElementsByTagName(t)[0]; }
        function _c(c) { return elt.getElementsByClassName(c)[0]; }
        
        var map = L.map(mapid);

        <?= \IdnoPlugins\Tracks\Tracks::getLMapdata($vars['object']->mapdata); ?>
                
        var el = L.control.elevation({
            position: "bottomleft",
            //theme: "steelblue-theme", //default: lime-theme
            width: 300,
            height: 100,
            margins: {
                top: 10,
                right: 20,
                bottom: 30,
                left: 50
            },
            useHeightIndicator: true, //if false a marker is drawn at map position
            interpolation: "linear", //see https://github.com/mbostock/d3/wiki/SVG-Shapes#wiki-area_interpolate
            hoverNumber: {
                decimalsX: 3, //decimals on distance (always in km)
                decimalsY: 0, //deciamls on height (always in m)
                formatter: undefined //custom formatter function may be injected
            },
            xTicks: undefined, //number of ticks in x axis, calculated by default according to width
            yTicks: undefined, //number of ticks on y axis, calculated by default according to height
            collapsed: false    //collapsed mode, show chart on click or mouseover
        });
	el.addTo(map);

        var g = new L.GPX(url, {
          async: true,
          marker_options: {
            startIconUrl: '<?php echo \Idno\Core\Idno::site()->config()->getDisplayURL() ?>IdnoPlugins/Tracks/external/leaflet-gpx/pin-icon-start.png',
            endIconUrl:   '<?php echo \Idno\Core\Idno::site()->config()->getDisplayURL() ?>IdnoPlugins/Tracks/external/leaflet-gpx/pin-icon-end.png',
            shadowUrl:    '<?php echo \Idno\Core\Idno::site()->config()->getDisplayURL() ?>IdnoPlugins/Tracks/external/leaflet-gpx/pin-shadow.png',
            },
        });
        g.on('loaded', function(e) {
            var gpx = e.target;
            map.fitBounds(gpx.getBounds());
            _t('h3').textContent          = gpx.get_name();
            _c('start').textContent       = gpx.get_start_time().toDateString() + ', ' + gpx.get_start_time().toLocaleTimeString();
            _c('duration').textContent    = gpx.get_duration_string(gpx.get_moving_time());
            if (gpx.get_average_hr()){
                _c('avghr').textContent       = gpx.get_average_hr();
            } else {
                $('#hr<?= $attachment['_id'] ?>').remove();
            }
            
    <?php
    if ($metric){
        ?>

          _c('distance').textContent        = gpx.m_to_km(gpx.get_distance()).toFixed(2);
          _c('pace').textContent            = gpx.get_duration_string(gpx.get_moving_pace(), true);
          _c('elevation-gain').textContent  = gpx.get_elevation_gain().toFixed(0);
          _c('elevation-loss').textContent  = gpx.get_elevation_loss().toFixed(0);
          _c('elevation-net').textContent   = (gpx.get_elevation_gain() - gpx.get_elevation_loss()).toFixed(0);
       <?php
        
    } else {
        ?>
          _c('distance').textContent        = gpx.get_distance_imp().toFixed(2);   
          _c('pace').textContent            = gpx.get_duration_string(gpx.get_moving_pace_imp(), true);
          _c('elevation-gain').textContent  = gpx.to_ft(gpx.get_elevation_gain()).toFixed(0);
          _c('elevation-loss').textContent  = gpx.to_ft(gpx.get_elevation_loss()).toFixed(0);
          _c('elevation-net').textContent   = gpx.to_ft(gpx.get_elevation_gain() - gpx.get_elevation_loss()).toFixed(0);
       <?php
    }
    ?>
        
        });
        g.on("addline",function(e){
            el.addData(e.line);
	});
        g.addTo(map);
      }
      
      display_gpx<?= $attachment['_id'] ?>(document.getElementById('map<?= $attachment['_id'] ?>'));
    </script>
        <?php
    }
}
?>
<?= $this->autop($this->parseHashtags($this->parseURLs($vars['object']->body, $rel))) ?>

<?php
if (!empty($vars['object']->tags)) {
    ?>
    <p class="tag-row"><i class="icon-tag"></i> <?= $this->parseHashtags($vars['object']->tags) ?></p>
<?php
}
