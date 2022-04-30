<?php

namespace App;

/**
 * This class allow to store paths used in the project as constants
 */
class Path
{
    const PUBLIC = '/public';

    const UPLOADS_CKFINDER = '/uploads/ckfinder';
    const UPLOADS_ADMIN_OPTIONS = '/uploads/admin/options';
    const UPLOADS_AUTHORS = '/uploads/authors';
    const UPLOADS_PROJECTS_GALLERY = '/uploads/projects/library';
    const UPLOADS_PROJECTS_THUMB = '/uploads/projects/thumbnail';
    const UPLOADS_POSTS = '/uploads/posts';
    const UPLOADS_LESSONS = '/uploads/lessons';

    const PROJECT_DEFAULT_IMG = 'placeholder.png';
    const POST_DEFAULT_IMG = 'placeholder.png';
    const AUTHOR_DEFAULT_IMG = 'placeholder.png';

    public static function APP_DIR()
    {
        return dirname(__DIR__);
    }
}
