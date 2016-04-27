<?php

namespace Domain\Model;

trait DynamicContent 
{
    /**
     * Returns an array of fields found in given content.
     *
     * @param string $content
     * @return array
     */
    final public function extract($content)
    {
        preg_match_all('/{{(.+?)}}/si', $content, $tags);

        return array_unique($tags[1]);
    }

    /**
     * Renders a content with proper values filled in handlebars.
     *
     * @param string $content
     * @param array $values
     * @return string
     */
    final protected function render($content, array $values)
    {
        foreach($values as $field => $value) {
            $content = preg_replace('/{{'.$field.'}}/si', $value, $content);
        }

        return $content;
    }
}
