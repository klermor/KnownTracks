<?php

    namespace IdnoPlugins\Tracks\Pages {

        class Delete extends \Idno\Common\Page {

            function getContent() {

                $this->createGatekeeper();    // This functionality is for logged-in users only

                // Are we loading an entity?
                if (!empty($this->arguments)) {
                    $object = \IdnoPlugins\Tracks\Tracks::getByID($this->arguments[0]);
                } else {
                    // TODO 404
                    $this->forward();
                }

                $t = \Idno\Core\site()->template();
                $body = $t->__(array(
                    'object' => $object
                ))->draw('entity/Status/delete');

                if (!empty($this->xhr)) {
                    echo $body;
                } else {
                    $t->__(array('body' => $body, 'title' => "Delete " . $object->getTitle()))->drawPage();
                }
            }

            function postContent() {
                $this->createGatekeeper();

                if (!empty($this->arguments)) {
                    $object = \IdnoPlugins\Text\Entry::getByID($this->arguments[0]);
                }
                if (empty($object)) $this->forward();
                if (!$object->canEdit()) {
                    $this->setResponse(403);
                    \Idno\Core\site()->session()->addErrorMessage("You don't have permission to perform this task.");
                    $this->forward();
                }

                if ($object->delete()) {
                    \Idno\Core\site()->session()->addMessage('Your tracks was deleted.');
                } else {
                    \Idno\Core\site()->session()->addErrorMessage("We couldn't delete your tracks.");
                }
                $this->forward($_SERVER['HTTP_REFERER']);
            }

        }

    }