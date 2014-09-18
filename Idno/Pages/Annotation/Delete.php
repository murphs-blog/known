<?php

    /**
     * Delete annotation endpoint.
     */

    namespace Idno\Pages\Annotation {

        /**
         * Default class to serve the homepage
         */
        class Delete extends \Idno\Common\Page
        {

            // No point doing get requests for delete functions


            // Handle POST requests 

            function postContent()
            {
                $this->gatekeeper();

                if (!empty($this->arguments[0])) {
                    $object = \Idno\Common\Entity::getByID($this->arguments[0]);
                    if (empty($object)) {
                        $object = \Idno\Common\Entity::getBySlug($this->arguments[0]);
                    }
                }
                if (empty($object)) {
                    $this->goneContent();
                }

                $permalink = $object->getURL() . '/annotations/' . $this->arguments[1];
		if ($object->canEditAnnotation($permalink)) {
                    if (($object->removeAnnotation($permalink)) && ($object->save())) {
                        //\Idno\Core\site()->session()->addMessage('The annotation was deleted.');
                    }
		    else{
                        // prior to mongodb 2.6 you could keep a dot in a field name; now you cant so both ways exist to support
                        // backward compatability
                        $mangledLink = str_replace('.','~',$permalink);
                        if ($object->canEditAnnotation($mangledLink)) {
                            if (($object->removeAnnotation($mangledLink)) && ($object->save())) {
                                //\Idno\Core\site()->session()->addMessage('The annotation was deleted.');
                            }
                        }
                    }
                }

                $this->forward($object->getURL() . '#comments');
            }

        }

    }
