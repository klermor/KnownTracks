<?php

    namespace IdnoPlugins\Tracks\Pages {

        class Edit extends \Idno\Common\Page {

            function getContent() {

                $this->createGatekeeper();    // This functionality is for logged-in users only

                // Are we loading an entity?
                if (!empty($this->arguments)) {
                    $object = \IdnoPlugins\Tracks\Tracks::getByID($this->arguments[0]);
                } else {
                    $object = new \IdnoPlugins\Tracks\Tracks();
                }

 		if ($owner = $object->getOwner()) {
                    $this->setOwner($owner);
                }

                $t = \Idno\Core\Idno::site()->template();
                $body = $t->__(array(
                    'object' => $object
                ))->draw('entity/Tracks/edit');

                if (empty($object)) {
                    $title = 'Upload GPX';
                } else {
                    $title = 'Edit GPX details';
                }

                if (!empty($this->xhr)) {
                    echo $body;
                } else {
                    $t->__(array('body' => $body, 'title' => $title))->drawPage();
                }
            }

            function postContent() {
                $this->createGatekeeper();

                $new = false;
                if (!empty($this->arguments)) {
                    $object = \IdnoPlugins\Tracks\Tracks::getByID($this->arguments[0]);
                }
                if (empty($object)) {
                    $object = new \IdnoPlugins\Tracks\Tracks();
                }

                if ($object->saveDataFromInput($this)) {
                    $forward = $this->getInput('forward-to', $object->getDisplayURL());
                    $this->forward($forward);
                }

            }

        }

    }