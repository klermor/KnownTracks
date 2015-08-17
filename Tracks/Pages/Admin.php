<?php

/**
 * Tracks administration
 */

namespace IdnoPlugins\Tracks\Pages {

    /**
     * Default class to serve Tracks settings in administration
     */
    class Admin extends \Idno\Common\Page {

        function getContent() {
            
            $this->adminGatekeeper(); // Admins only
            $t = \Idno\Core\site()->template();
            $body = $t->draw('admin/tracks');
            $t->__(array('title' => 'Tracks settings', 'body' => $body))->drawPage();
        }

        function postContent() {

            $this->adminGatekeeper(); // Admins only

            $metric = $this->getInput('metric');
            $mapdata = $this->getInput('mapdata');
            $weight = $this->getInput('weight');
            $height = $this->getInput('height');

            \Idno\Core\site()->config->config['tracks'] = array(
                'metric'=>$metric,
                'mapdata' => $mapdata,
                'weight' => $weight,
                'height' => $height
            );
            \Idno\Core\site()->config()->save();
            \Idno\Core\site()->session()->addMessage('Your Tracks settings were saved.');
            $this->forward(\Idno\Core\site()->config()->getDisplayURL() . 'admin/tracks/');
        }

    }

}