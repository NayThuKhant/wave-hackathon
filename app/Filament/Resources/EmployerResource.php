<?php

namespace App\Filament\Resources;

use App\Enums\SystemStatus;
use App\Filament\Resources\EmployerResource\Pages;
use App\Helpers\Enum;
use App\Models\Employer;
use App\Traits\HasFilamentComponents;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EmployerResource extends Resource
{
    use HasFilamentComponents;

    protected static ?string $model = Employer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    // TODO User in this application won't be created from the admin panel, they have to register themselves
    public static function canCreate(): bool
    {
        return array_key_exists('create', static::getPages());
    }

    // TODO Deleting a user in this application is not allowed for now
    public static function canDelete(Model $record): bool
    {
        return false;
        //return parent::canDelete($record); // TODO: Change the autogenerated stub
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('User Profile')
                    ->relationship('user')
                    ->schema(
                        [
                            TextInput::make('name')->required()->maxLength(256)->disabled(),
                            TextInput::make('email')->required()->email()->disabled(),
                        ]
                    ),
                Select::make('system_status')->options(Enum::make(SystemStatus::class)->toArray()),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('system_status')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                self::buildSystemStatusFilter(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                //Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListEmployers::route('/'),
            //'create' => Pages\CreateEmployer::route('/create'),
            'edit' => Pages\EditEmployer::route('/{record}/edit'),
        ];
    }
}
