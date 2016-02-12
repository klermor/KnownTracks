<?php

namespace IdnoPlugins\Tracks {

    class Main extends \Idno\Common\Plugin {

        function registerPages() {

            \Idno\Core\Idno::site()->addPageHandler('admin/tracks', '\IdnoPlugins\Tracks\Pages\Admin');

            \Idno\Core\Idno::site()->addPageHandler('/tracks/edit/?', '\IdnoPlugins\Tracks\Pages\Edit');
            \Idno\Core\Idno::site()->addPageHandler('/tracks/edit/([A-Za-z0-9]+)/?', '\IdnoPlugins\Tracks\Pages\Edit');
            \Idno\Core\Idno::site()->addPageHandler('/tracks/delete/([A-Za-z0-9]+)/?', '\IdnoPlugins\Tracks\Pages\Delete');

            \Idno\Core\Idno::site()->template()->extendTemplate('admin/menu/items', 'admin/tracks/menu');

            \Idno\Core\Idno::site()->template()->extendTemplate('shell/head', 'tracks/shell/head');
        }

    }

}
    