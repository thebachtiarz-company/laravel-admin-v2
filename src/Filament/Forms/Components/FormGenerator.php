<?php

namespace TheBachtiarz\Admin\Filament\Forms\Components;

class FormGenerator
{
    public const STRING = 'string';
    public const NUMERIC = 'numeric';
    public const SELECT = 'select';
    public const RADIO = 'radio';
    public const CHECKBOX = 'checkbox';
    public const TOGGLE = 'toggle';
    public const DATE = 'date';
    public const TIME = 'time';
    public const DATE_TIME = 'date_time';
    public const TEXTAREA = 'textarea';

    /**
     * Form Components
     *
     * @var array<string,\Filament\Forms\Components\Field>
     */
    private static array $formComponents = [
        self::STRING => \Filament\Forms\Components\TextInput::class,
        self::NUMERIC => \Filament\Forms\Components\TextInput::class,
        self::SELECT => \Filament\Forms\Components\Select::class,
        self::RADIO => \Filament\Forms\Components\Radio::class,
        self::CHECKBOX => \Filament\Forms\Components\Checkbox::class,
        self::TOGGLE => \Filament\Forms\Components\Toggle::class,
        self::DATE => \Filament\Forms\Components\DatePicker::class,
        self::TIME => \Filament\Forms\Components\TimePicker::class,
        self::DATE_TIME => \Filament\Forms\Components\DateTimePicker::class,
        self::TEXTAREA => \Filament\Forms\Components\Textarea::class
    ];

    /**
     * Generate form using class object
     *
     * @param class-string $classObject
     * @param array<string,string> $attributeTypes
     * @param array<string,\Closure> $attributeComponents
     * @return array<string,\Filament\Forms\Components\Field>
     */
    public static function generate(string $classObject, array $attributeTypes = [], array $attributeComponents = []): array
    {
        $components = [];

        foreach (get_class_vars($classObject) as $attribute => $value) {
            $component = static::getComponent(type: @$attributeTypes[$attribute] ?? static::STRING, componentId: $attribute);

            if (@$attributeComponents[$attribute]) {
                $component = $attributeComponents[$attribute]($component);
            }

            $components[] = $component;
        }

        return $components;
    }

    /**
     * Collect form component(s)
     *
     * @param array<string,\Filament\Forms\Components\Field> $components Add custom form(s)
     * @return array<string,\Filament\Forms\Components\Field>
     */
    public static function components(array $components = []): array
    {
        if (count($components)) {
            static::$formComponents = array_merge(static::$formComponents, $components);
        }

        return static::$formComponents;
    }

    /**
     * Get form component
     *
     * @param string $type
     * @param string $componentId
     * @return \Filament\Forms\Components\Field
     */
    public static function getComponent(string $type, string $componentId): \Filament\Forms\Components\Field
    {
        return match ($type) {
            self::STRING => static::components()[self::STRING]::make($componentId)->string(),
            self::NUMERIC => static::components()[self::NUMERIC]::make($componentId)->numeric(),
            self::SELECT => static::components()[self::SELECT]::make($componentId)->options([])->native(false),
            self::RADIO => static::components()[self::RADIO]::make($componentId)->options([]),
            self::CHECKBOX => static::components()[self::CHECKBOX]::make($componentId),
            self::TOGGLE => static::components()[self::TOGGLE]::make($componentId),
            self::DATE => static::components()[self::DATE]::make($componentId)->string()->native(false),
            self::TIME => static::components()[self::TIME]::make($componentId)->string()->native(false),
            self::DATE_TIME => static::components()[self::DATE_TIME]::make($componentId)->string()->native(false),
            self::TEXTAREA => static::components()[self::TEXTAREA]::make($componentId)->string()->rows(3),
            default => static::components()[self::STRING]::make($componentId)->string(),
        };
    }
}
