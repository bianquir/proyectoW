<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ChatPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-s-chat-bubble-oval-left';
    protected static ?string $navigationLabel = 'Chat';
    protected static string $view = 'filament.pages.chat-page';
    
    public function getTitle(): string
    {
        return ''; 
    }
}
