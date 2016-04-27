<?php

namespace AppBundle\Entity\Traits;

use DOMDocument;

trait SanitizeHtml
{
    public function stripScript($html)
    {
        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $script = $doc->getElementsByTagName('script');

        $remove = [];
        foreach ($script as $item) {
            $remove[] = $item;
        }

        foreach ($remove as $item) {
            $item->parentNode->removeChild($item);
        }

        return $doc->saveHTML();
    }
}
