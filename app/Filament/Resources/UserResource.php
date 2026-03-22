<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\ThreadsRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                Forms\Components\TextInput::make('name')
//                    ->required()
//                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->disabled()
                    ->dehydrated(false),
//                Forms\Components\TextInput::make('email')
//                    ->email()
//                    ->required()
//                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('email')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'student' => 'Student',
                    ])
                    ->required()
                    ->native(false),

//                Forms\Components\TextInput::make('password')
//                    ->password()
//                    ->revealable()
//                    ->dehydrated(fn ($state) => filled($state))
//                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
//                    ->required(fn (string $operation): bool => $operation === 'create')
//                    ->helperText('Leave empty when editing if you do not want to change the password.'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'danger' => 'admin',
                        'gray' => 'student',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('threads_count')
                    ->counts('threads')
                    ->label('Threads')
                    ->sortable(),

                Tables\Columns\TextColumn::make('comments_count')
                    ->counts('comments')
                    ->label('Comments')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'student' => 'Student',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

//    public static function getRelations(): array
//    {
//        return [
//            RelationManagers\ThreadsRelationManager::class,
//            RelationManagers\CommentsRelationManager::class,
//        ];
//    }
    public static function getRelations(): array
    {
        return [
            ThreadsRelationManager::class,
            CommentsRelationManager::class,
        ];
    }
}
