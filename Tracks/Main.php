<?php

namespace IdnoPlugins\Tracks {

    class Main extends \Idno\Common\Plugin {

        function registerPages() {

            // Administration page
            \Idno\Core\site()->addPageHandler('admin/tracks', '\IdnoPlugins\Tracks\Pages\Admin');

            \Idno\Core\site()->addPageHandler('/tracks/edit/?', '\IdnoPlugins\Tracks\Pages\Edit');
            \Idno\Core\site()->addPageHandler('/tracks/edit/([A-Za-z0-9]+)/?', '\IdnoPlugins\Tracks\Pages\Edit');
            \Idno\Core\site()->addPageHandler('/tracks/delete/([A-Za-z0-9]+)/?', '\IdnoPlugins\Tracks\Pages\Delete');

            \Idno\Core\site()->template()->extendTemplate('admin/menu/items', 'admin/tracks/menu');

//                \Idno\Core\site()->template()->extendTemplate('shell/footer','tracks/shell/footer');
            \Idno\Core\site()->template()->extendTemplate('shell/head', 'tracks/shell/head');
        }

    }

}
    