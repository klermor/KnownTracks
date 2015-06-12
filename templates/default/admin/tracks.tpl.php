<?php

$mapdata   = \Idno\Core\site()->config()->tracks['mapdata'];
if (empty($mapdata)){
    $mapdata = 'osm';
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

<div class="row">

    <div class="span10 offset1">
        <?= $this->draw('admin/menu') ?>
        <h1>Tracks settings</h1>

<!--        <div class="explanation">
            <p>
                ...
            </p>
        </div>-->
    </div>
    <div class="span10 offset1">
        <form action="<?= \Idno\Core\site()->config()->getDisplayURL() ?>admin/tracks" class="form-horizontal" method="post">
            
            <div class="control-group">
	                 
                <label class="control-label" for="name">Metric </label>
                <div class="config-toggle">
                    <input type="checkbox" data-toggle="toggle" data-onstyle="info" data-on="Yes" data-off="No"
                           name="metric"
                           value="true" <?php if (\Idno\Core\site()->config()->tracks['metric'] == true) echo 'checked'; ?>>
                </div>
            </div>

            <div class="control-group">
	                 
                <label class="control-label" for="name">MapData</label>
                <div class="controls">
                    <select name="mapdata">
                        <?php
                            foreach ([
                                'OpenStreetMap'=> 'osm',
                                'Thunderforest Outdoors' => 'thunderforest'
                                     ] as $field => $value) {
                                ?>
                                <option
                                    value="<?= $value; ?>" <?php if ($mapdata === $value) {
                                    echo "selected";
                                } ?>><?= $field; ?></option>
                            <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
            
             <div class="control-group">
	                 
                <label class="control-label" for="name">Weight</label>
                <div class="controls">
                    <input type="text" id="name" placeholder="98%" class="span6" name="weight" value="<?= $weight ?>" >
                </div>
            </div>
            
            <div class="control-group">
	                 
                <label class="control-label" for="name">Height</label>
                <div class="controls">
                    <input type="text" id="name" placeholder="300px" class="span6" name="height" value="<?= $height ?>" >
                </div>
            </div>

            <div class="control-group">
                <div class="controls-save">
                    <button type="submit" class="btn btn-primary">Save settings</button>
                </div>
            </div>

            <?= \Idno\Core\site()->actions()->signForm('/admin/tracks') ?>
        </form>
    </div>
   
</div>