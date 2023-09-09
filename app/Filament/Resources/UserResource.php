<?php

namespace App\Filament\Resources;

use App\Enums\Gender;
use App\Enums\SystemStatus;
use App\Filament\Resources\UserResource\Pages;
use App\Helpers\Enum;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

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
        //return parent::canDelete($record);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(256),
                Forms\Components\TextInput::make('email')->required()->email(),
                Forms\Components\DatePicker::make('dob')->required(),
                Forms\Components\TextInput::make('nrc')->required(),
                Forms\Components\TextInput::make('country_code')->required(),
                Forms\Components\TextInput::make('mobile_number')->required(),
                Forms\Components\Select::make('gender')->options(Enum::make(Gender::class)->toArray()),

                Forms\Components\Fieldset::make('Employer Profile')
                    ->relationship('employer')
                    ->schema(
                        [
                            Forms\Components\Select::make('system_status')->options(Enum::make(SystemStatus::class)->toArray()),
                        ]
                    ),

                Forms\Components\Fieldset::make('Employee Profile')
                    ->relationship('employee')
                    ->schema(
                        [
                            Forms\Components\Select::make('system_status')->options(Enum::make(SystemStatus::class)->toArray()),
                        ]
                    ),
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

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nrc')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('country_code')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mobile_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('dob')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender')
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
            'index' => Pages\ListUsers::route('/'),
            //'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
