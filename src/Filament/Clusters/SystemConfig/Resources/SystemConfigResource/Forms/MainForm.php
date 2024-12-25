<?php

namespace TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource\Forms;

use Filament\Forms;
use TheBachtiarz\Config\Http\Requests\Rules\ConfigPathRule;
use TheBachtiarz\Config\Http\Requests\Rules\ConfigValueRule;
use TheBachtiarz\Config\Interfaces\Models\ConfigInterface;

class MainForm
{
    public static function form(): Forms\Components\Group
    {
        return Forms\Components\Group::make()->schema([
            Forms\Components\TextInput::make(ConfigInterface::ATTRIBUTE_PATH)->label('Config Path')->inlineLabel()
                ->prefixIcon('heroicon-o-wrench')
                ->required()
                ->rules(ConfigPathRule::rules()[ConfigPathRule::PATH])
                ->disabledOn('edit')->dehydrated()
                ->columnSpanFull(),
            Forms\Components\TextInput::make(ConfigInterface::ATTRIBUTE_VALUE)->label('Config Value')->inlineLabel()
                ->prefixIcon('heroicon-o-document')
                ->required()
                ->rules(ConfigValueRule::rules()[ConfigValueRule::VALUE])
                ->columnSpanFull(),
            Forms\Components\Toggle::make(ConfigInterface::ATTRIBUTE_IS_ENCRYPT)->label('Encrypt Config Value')->inlineLabel()
                ->helperText('Encrypt the config value to secure sensitive data')
                ->onIcon('heroicon-c-check-circle')->onColor('info')
                ->offIcon('heroicon-c-x-circle')->offColor('success')
                ->columnSpanFull(),
        ])->columns(12)->columnStart(['sm' => 'full', 'md' => 2])->columnSpan(['sm' => 'full', 'md' => 10]);
    }
}
