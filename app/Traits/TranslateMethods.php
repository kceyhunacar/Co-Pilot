<?php

namespace App\Traits;

use Spatie\Translatable\HasTranslations as BaseHasTranslations;

trait TranslateMethods
{
    use BaseHasTranslations;

    public function toArray()
    {

        $attributes = $this->attributesToArray(); // attributes selected by the query
        // remove attributes if they are not selected
        $translatables = array_filter($this->getTranslatableAttributes(), function ($key) use ($attributes) {
            return array_key_exists($key, $attributes);
        });
        // dd(request());
        $locale = request('locale')??app()->getLocale();
        foreach ($translatables as $field) {
            $attributes[$field] = $this->getTranslation($field, $locale);
        }
        return array_merge($attributes, $this->relationsToArray());
    }
}
