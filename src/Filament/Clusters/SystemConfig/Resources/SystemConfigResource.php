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
use TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource\Forms\MainForm;
use TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource\Pages;
use TheBachtiarz\Admin\Traits\Filament\Resources\HasAuthorizedResource;
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
                    MainForm::form(),
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
                Tables\Columns\TextColumn::make(ConfigInterface::VALUE_FORMATTED)->label('Config Value')
                    ->fontFamily(FontFamily::Mono)->weight(FontWeight::SemiBold)
                    ->limit(50),
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
                Tables\Filters\TernaryFilter::make(ConfigInterface::ATTRIBUTE_IS_ENCRYPT)->label('Is Encrypted')
                    ->trueLabel('Yes')->falseLabel('No')
                    ->queries(
                        true: fn(Builder $builder): Builder => $builder->where(ConfigInterface::ATTRIBUTE_IS_ENCRYPT, true),
                        false: fn(Builder $builder): Builder => $builder->where(ConfigInterface::ATTRIBUTE_IS_ENCRYPT, false),
                        blank: fn(Builder $builder): Builder => $builder,
                    )
                    ->native(false),
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
