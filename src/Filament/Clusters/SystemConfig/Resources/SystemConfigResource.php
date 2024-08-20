<?php

namespace TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource\Pages;
use TheBachtiarz\Admin\Traits\Filament\Resources\HasAuthorizedResource;
use TheBachtiarz\Config\Http\Requests\Rules\ConfigPathRule;
use TheBachtiarz\Config\Http\Requests\Rules\ConfigValueRule;
use TheBachtiarz\Config\Interfaces\Models\ConfigInterface;
use TheBachtiarz\Config\Models\Config;

class SystemConfigResource extends Resource
{
    use HasAuthorizedResource;

    protected static ?string $modelLabel = 'System Config';

    protected static ?int $navigationSort = 30;

    protected static ?string $slug = 'system-config';

    protected static ?string $model = Config::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Group::make()->schema([
                        Forms\Components\TextInput::make(ConfigInterface::ATTRIBUTE_PATH)->label('Config Path')->inlineLabel()
                            ->prefixIcon('heroicon-o-wrench')
                            ->required()
                            ->rules(ConfigPathRule::rules()[ConfigPathRule::PATH])
                            ->live()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make(ConfigInterface::ATTRIBUTE_VALUE)->label('Config Value')->inlineLabel()
                            ->prefixIcon('heroicon-o-document')
                            ->required()
                            ->rules(ConfigValueRule::rules()[ConfigValueRule::VALUE])
                            ->live()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make(ConfigInterface::ATTRIBUTE_IS_ENCRYPT)->label('Encrypt Config Value')->inlineLabel()
                            ->live()
                            ->onIcon('heroicon-c-check-circle')->onColor('info')
                            ->offIcon('heroicon-c-x-circle')->offColor('success')
                            ->columnSpanFull(),
                    ])->columns(12)->columnStart(['sm' => 'full', 'md' => 2])->columnSpan(['sm' => 'full', 'md' => 10]),
                ])->columns(12)->columnSpanFull(),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(ConfigInterface::ATTRIBUTE_PATH)->label('Config Path')
                    ->fontFamily(FontFamily::Mono)->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->sortable()
                    ->copyable()->copyMessage('Config path copied')->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make(ConfigInterface::ATTRIBUTE_VALUE)->label('Config Value')
                    ->fontFamily(FontFamily::Mono)->weight(FontWeight::SemiBold),
                Tables\Columns\TextColumn::make(ConfigInterface::ATTRIBUTE_IS_ENCRYPT)->label('Is Encrypted')
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Yes' : 'No')
                    ->badge()->color(fn(bool $state): string => $state ? 'info' : 'success')
                    ->fontFamily(FontFamily::Mono)
                    ->alignCenter(),
                Tables\Columns\TextColumn::make(ConfigInterface::ATTRIBUTE_CREATED_AT)->label('Created')
                    ->fontFamily(FontFamily::Mono)
                    ->since(),
            ])
            ->filters([
                Tables\Filters\Filter::make(ConfigInterface::ATTRIBUTE_IS_ENCRYPT)->label('Is Encrypted')
                    ->query(fn(): Builder => static::$model::query()->where(column: ConfigInterface::ATTRIBUTE_IS_ENCRYPT, operator: '=', value: 1))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSystemConfigs::route('/'),
            'create' => Pages\CreateSystemConfig::route('/create'),
            'edit' => Pages\EditSystemConfig::route('/{record}/edit'),
        ];
    }
}
