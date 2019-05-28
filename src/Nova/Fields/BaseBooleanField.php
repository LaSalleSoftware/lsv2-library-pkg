<?php

/**
 * This file is part of the Lasalle Software library (lasallesoftware/library)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca Blog, Podcast, Docs
 * @link       https://packagist.org/packages/lasallesoftware/library Packagist
 * @link       https://github.com/lasallesoftware/library GitHub
 *
 */

namespace Lasallesoftware\Library\Nova\Fields;

// Laravel Nova class
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

// LaSalle Software class
use Lasallesoftware\Library\Nova\Fields\BaseField;

/**
 * Class BaseBooleanField
 *
 * @package Lasallesoftware\Library\Nova\Fields
 */
class BaseBooleanField extends BaseField
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'boolean-field';

    /**
     * The value to be used when the field is "true".
     *
     * @var bool
     */
    public $trueValue = true;

    /**
     * The value to be used when the field is "false".
     *
     * @var bool
     */
    public $falseValue = false;

    /**
     * The text alignment for the field's text in tables.
     *
     * @var string
     */
    public $textAlign = 'center';


    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return void
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if (isset($request[$requestAttribute])) {
            $model->{$attribute} = $request[$requestAttribute] == 1
                ? $this->trueValue : $this->falseValue;
        }
    }

    /**
     * Specify the values to store for the field.
     *
     * @param  mixed  $trueValue
     * @param  mixed  $falseValue
     * @return $this
     */
    public function values($trueValue, $falseValue)
    {
        return $this->trueValue($trueValue)->falseValue($falseValue);
    }

    /**
     * Specify the value to store when the field is "true".
     *
     * @param  mixed  $value
     * @return $this
     */
    public function trueValue($value)
    {
        $this->trueValue = $value;

        return $this;
    }

    /**
     * Specify the value to store when the field is "false".
     *
     * @param  mixed  $value
     * @return $this
     */
    public function falseValue($value)
    {
        $this->falseValue = $value;

        return $this;
    }
}
