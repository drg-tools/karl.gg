<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Comments
    |--------------------------------------------------------------------------
    |
    | Limits for the comment threads on loadout pages. Guests can comment,
    | signed-in users can vote and report, and moderators manage everything
    | from the Filament admin panel.
    |
    */

    // Maximum comment length, in characters.
    'max_length' => 500,

    // Top-level comments per page.
    'per_page' => 15,

    // How long authors may edit or delete their own comments, in seconds.
    'edit_window' => 60 * 60 * 24 * 30,

    // Comments with at least this many links are held for moderation.
    'max_links' => 2,

    // Authors with this many comments awaiting moderation cannot post more.
    'max_unapproved' => 5,

    // Comments with this many reports are held for moderation.
    'max_reports' => 2,

    // Comments a non-moderator may post per minute.
    'posts_per_minute' => 5,

];
