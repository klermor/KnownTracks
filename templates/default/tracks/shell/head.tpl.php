<?php
if (!\Idno\Core\site()->plugins()->get('Checkin')){
//if (!in_array('Checkin', \Idno\Core\site()->config->config['plugins'])){
?>
<link rel="stylesheet" href="<?php echo \Idno\Core\site()->config()->getDisplayURL() ?>IdnoPlugins/Tracks/external/leaflet/leaflet.css"/>
<!--[if lte IE 8]>
<link rel="stylesheet" href="<?php echo \Idno\Core\site()->config()->getDisplayURL() ?>IdnoPlugins/Tracks/external/leaflet/leaflet.ie.css"/>
<![endif]-->
<script type="text/javascript" src="<?php echo \Idno\Core\site()->config()->getDisplayURL() ?>IdnoPlugins/Tracks/external/leaflet/leaflet.js"></script>
<?php
} 

$weight = \Idno\Core\site()->config()->tracks['weight'];
if (empty($weight)){
    $weight = '98%';
}
$height = \Idno\Core\site()->config()->tracks['height'];
if (empty($height)){
    $height = '300px';
}
?> 
<script type="text/javascript" src="<?php echo \Idno\Core\site()->config()->getDisplayURL() ?>IdnoPlugins/Tracks/external/leaflet-gpx/gpx.js"></script>
<style type="text/css">
      .gpx { border: 5px #aaa solid; border-radius: 5px;
        box-shadow: 0 0 3px 3px #ccc;
        width: <?=$weight?>; margin: 1em auto; }
      .gpx header { padding: 0.5em; }
      .gpx h3 { margin: 0; padding: 0; font-weight: bold; }
      .gpx .start { font-size: smaller; color: #444; }
      .gpx .map { border: 1px #888 solid; border-left: none; border-right: none;
        width: <?=$weight?>; height: <?=$height?>; margin: 0; }
      .gpx footer { background: #f0f0f0; padding: 0.5em; }
      .gpx ul.info { list-style: none; margin: 0; padding: 0; font-size: smaller; }
      .gpx ul.info li { color: #666; padding: 2px; display: inline; }
      .gpx ul.info li span { color: black; }
</style>
