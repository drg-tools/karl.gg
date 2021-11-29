<?php

namespace Hazzard\Comments;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

class SettingInput
{
    const TEXT_TYPE = 'text';
    const NUMBER_TYPE = 'number';
    const SELECT_TYPE = 'select';
    const BOOLEAN_TYPE = 'boolean';
    const TEXTAREA_TYPE = 'textarea';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param  string $type
     * @param  array $data
     * @return void
     */
    public function __construct($type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
        $this->key = $data['key'];

        $value = Config::get('comments.'.$this->key);

        if (is_array($value)) {
            $value = implode('\n', $value);
        }

        if ($type === self::BOOLEAN_TYPE || $type === self::SELECT_TYPE) {
            $this->data['selected'] = $value;
        } else {
            $this->data['value'] = $value;
        }

        if (! isset($this->data['label'])) {
            $this->data['label'] = Lang::get('comments::admin.'.$this->key.'_label');
        }

        if (! isset($this->data['help'])) {
            $this->data['help'] = Lang::get('comments::admin.'.$this->key.'_help');
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return \Illuminate\Support\HtmlString
     */
    public function render()
    {
        return new HtmlString(
            View::make('comments::admin.input-types.'.$this->type, $this->data)
        );
    }

    /**
     * Create a boolean input.
     *
     * @param  string $key
     * @param  string $label
     * @param  string|null $help
     * @return static
     */
    public static function boolean($key, $label = null, $help = null)
    {
        return new static(self::BOOLEAN_TYPE, compact('key', 'label', 'help'));
    }

    /**
     * Create a text input.
     *
     * @param  string $key
     * @param  string $label
     * @param  string|null $help
     * @return static
     */
    public static function text($key, $label = null, $help = null)
    {
        return new static(self::TEXT_TYPE, compact('key', 'label', 'help'));
    }

    /**
     * Create a textarea input.
     *
     * @param  string $key
     * @param  string $label
     * @param  string|null $help
     * @return static
     */
    public static function textarea($key, $label = null, $help = null)
    {
        return new static(self::TEXTAREA_TYPE, compact('key', 'label', 'help'));
    }

    /**
     * Create a number input.
     *
     * @param  string $key
     * @param  string $label
     * @param  string|null $help
     * @return static
     */
    public static function number($key, $label = null, $help = null)
    {
        return new static(self::NUMBER_TYPE, compact('key', 'label', 'help'));
    }

    /**
     * Create a select input.
     *
     * @param  string $key
     * @param  string $label
     * @param  string|null $help
     * @return static
     */
    public static function select($key, array $options, $label = null, $help = null)
    {
        $keys = array_keys($options);

        if (array_keys($keys) === $keys) {
            $temp = $options;
            $options = [];

            foreach ($temp as $option) {
                $options[$option] = $option;
            }
        }

        return new static(self::SELECT_TYPE, compact('key', 'label', 'help', 'options'));
    }
}
