<?php
$mapdata   = \Idno\Core\site()->config()->tracks['mapdata'];
$mapdataobj = $vars['object']->mapdata;

if (empty($mapdataobj)){
    $mapdataobj = $mapdata;
}
?>

<?=$this->draw('entity/edit/header');?>

<?php
if (empty($vars['object']->_id)) {
?>
<style>
.mapdata-info {
    display: none;
}
</style>
<script>
$('.advanced').click(function(){
    $('.mapdata-info').slideToggle();
});
</script>
<?php
}
?>
<form action="<?=$vars['object']->getURL()?>" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="span8 offset2 edit-pane">
            <h4>
            <?php
                if (empty($vars['object']->_id)) {
                       echo 'New Track GPX';
                    } else {
                        echo 'Edit Track description';
                    }
                ?>
            </h4>
            <p>
            <?php
                if (empty($vars['object']->_id)) {
                ?>
                    <label>
                        <span class="btn btn-primary btn-file">
                            <i class="icon-play-circled"></i> <span id="tracks-filename">Upload tracks</span> <input type="file" name="tracks" id="tracks" class="span9" accept="application/gpx+xml" onchange="$('#tracks-filename').html($(this).val())" />
                        </span>
                    </label>
                <?php
                }
                ?>
            </p>
            <p>
                <label>
                    Title<br />
                    <input type="text" name="title" id="title" placeholder="Give it a title" value="<?=htmlspecialchars($vars['object']->title)?>" class="span8" />
                </label>
            </p>
            <p>
                <label>
                    Description<br />
                    <textarea name="body" id="description" placeholder="Add caption" class="span8 bodyInput"><?=htmlspecialchars($vars['object']->body)?></textarea>
                </label>
            </p>
            <?php
            if (empty($vars['object']->_id)) {
            ?>
            <button type="button" class="btn btn-inverse advanced">Advanced</button>
            <?php
            }
            ?>
           <p class="mapdata-info">
                <label>Map data<br />
                     <select name="mapdata">
                        <?php
                            foreach ([
                                'OpenStreetMap'=> 'osm',
                                'Thunderforest Outdoors' => 'thunderforest'
                                     ] as $field => $value) {
                                ?>
                                <option
                                    value="<?= $value; ?>" <?php if ($mapdataobj === $value) {
                                    echo "selected";
                                } ?>><?= $field; ?></option>
                            <?php
                            }
                        ?>
                    </select>
                </label>
            </p>

            <?=$this->draw('entity/tags/input');?>
            <?php if (empty($vars['object']->_id)) echo $this->drawSyndication('tracks'); ?>
            <?php if (empty($vars['object']->_id)) { ?><input type="hidden" name="forward-to" value="<?= \Idno\Core\site()->config()->getDisplayURL() . 'content/all/'; ?>" /><?php } ?>
            <p class="button-bar ">
                <?= \Idno\Core\site()->actions()->signForm('/tracks/edit') ?>
                <input type="button" class="btn btn-cancel" value="Cancel" onclick="hideContentCreateForm();" />
                <input type="submit" class="btn btn-primary" value="Publish" />
                <?= $this->draw('content/access'); ?>
            </p>
            
            
        </div>

    </div>
</form>
<?=$this->draw('entity/edit/footer');?>