<?php

namespace IdnoPlugins\Tracks {

    class Tracks extends \Idno\Common\Entity {

        function getTitle() {
            if (empty($this->title)) {
                return 'Untitled';
            } else {
                return $this->title;
            }
        }

        function getDescription() {
            return $this->body;
        }

        function getMapdata() {
            return $this->mapdata;
        }

        /**
         * Tracks objects have type 'tracks'
         * @return 'tracks'
         */
        function getActivityStreamsObjectType() {
            return 'tracks';
        }

        /**
         * Saves changes to this object based on user input
         * @return bool
         */
        function saveDataFromInput() {

            if (empty($this->_id)) {
                $new = true;
            } else {
                $new = false;
            }

            if ($new) {
                if (!\Idno\Core\site()->triggerEvent("file/upload", [], true)) {
                    return false;
                }
            }

            $this->title    = \Idno\Core\site()->currentPage()->getInput('title');
            $this->body     = \Idno\Core\site()->currentPage()->getInput('body');
            $this->tags     = \Idno\Core\site()->currentPage()->getInput('tags');
            $this->access   = \Idno\Core\site()->currentPage()->getInput('access');
            $this->mapdata  = \Idno\Core\site()->currentPage()->getInput('mapdata');

            $this->setAccess($this->access);

            if ($time = \Idno\Core\site()->currentPage()->getInput('created')) {
                if ($time = strtotime($time)) {
                    $this->created = $time;
                }
            }

            // This flag will tell us if it's safe to save the object later on
            if ($new) {
                $ok = false;
            } else {
                $ok = true;
            }

            // Get tracks
            if ($new) {
                // This is awful, but unfortunately, browsers can't be trusted to send the right mimetype.
                $ext = pathinfo($_FILES['tracks']['name'], PATHINFO_EXTENSION);
                if (!empty($ext)) {
                    if (in_array($ext, array('gpx'))) {
                        $tracks_file = $_FILES['tracks'];
                        if ($tracks_file['type'] == 'application/gpx+xml') {
                            switch ($ext) {
                                case 'gpx':
                                    $tracks_file['type'] = 'gpx';
                                    break;
                            }
                        }
                        $this->tracks_type = $tracks_file['type'];
                        if ($tracks = \Idno\Entities\File::createFromFile($tracks_file['tmp_name'], $tracks_file['name'], $tracks_file['type'], true)) {
                            $this->attachFile($tracks);
                            $ok = true;
                        } else {
                            \Idno\Core\site()->session()->addErrorMessage('Tracks wasn\'t attached.');
                        }
                    } else {
                        \Idno\Core\site()->session()->addErrorMessage('This doesn\'t seem to be a track file .. ' . $_FILES['tracks']['type']);
                    }
                } else {
                    \Idno\Core\site()->session()->addErrorMessage('We couldn\'t access your track. Please try again.');

                    return false;
                }
            }

            // If a tracks file wasn't attached, don't save the file.
            if (!$ok) {
                return false;
            }

            if ($this->save($new)) {
                \Idno\Core\Webmention::pingMentions($this->getURL(), \Idno\Core\site()->template()->parseURLs($this->getTitle() . ' ' . $this->getDescription()));

                return true;
            } else {
                return false;
            }
        }

        /**
         * Get Leaflet Map data
         * 
         * @param $mapdata
         * @return string
         */
        function getLMapdata($mapdata) {

            if (empty($mapdata)) {
                $mapdata = \Idno\Core\site()->config()->tracks['mapdata'];
            }

            switch ($mapdata) {
                case 'mapquest':
                    $map = "L.tileLayer('http://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg', {
                        subdomains:\"1234\",
          attribution: 'Map data &copy; <a href=\"http://openstreetmap.org\">OpenStreetMap</a>. Tiles Courtesy of <a href=\"http://www.mapquest.com/\" target=\"_blank\">MapQuest</a>'
        }).addTo(map);";

                    break;

                case 'thunderforest':

                    $map = "L.tileLayer('http://{s}.tile.thunderforest.com/outdoors/{z}/{x}/{y}.png', {
          attribution: 'Map &copy; <a href=\"http://www.thunderforest.com\" target=\"_blank\">Thunderforest</a>, Data &copy; <a href=\"http://openstreetmap.org\" target=\"_blank\">OpenStreetMap</a>'
        }).addTo(map);";

                    break;

                default:
                   $map = "L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: 'Map data &copy; <a href=\"http://www.osm.org\" target=\"_blank\">OpenStreetMap</a>'
        }).addTo(map);";
            }

            return $map;
        }

    }

}