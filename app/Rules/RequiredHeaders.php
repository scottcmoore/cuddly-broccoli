<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiredHeaders implements Rule
{
    protected $requiredHeadersArray = array(
        'sku',
        'title',
        'description',
        'price',
        'availability',
        'color',
        'dimensions',
    );

    /**
     * Return false if the headers in our incoming CSV are not the same as our required headers (case-sensitive).
     * 
     * @param array $headers the incoming CSV's first row
     * @return bool
     */
    public function requiredHeadersPresent(array $headers) {
        if (count(array_diff($headers, $this->requiredHeadersArray))) {
            return false;
        }
        return true;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->requiredHeadersPresent($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The CSV provided did not have the correct headers. The headers should be: ' . implode(', ', $this->requiredHeadersArray);
    }
}
