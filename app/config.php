<?php

const SITE_NAME = 'Photo Feed';

const DB_HOST = 'MySQL-8.4:3306';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'photo_feed_db';

const LOGIN_FLAG = 'logged_in';
const PASS_MIN = 8;
const PASS_MAX = 20;
const LOGIN_MIN = 3;
const LOGIN_MAX = 15;
const EMAIL_MIN = 6;
const EMAIL_MAX = 25;
const DESCRIPTION_MAX = 255;

const LOGIN_DESCRIPTION = "Create an account or log in to Instagram — share interesting moments with people who understand you.";
const REGISTRATION_DESCRIPTION = "Create an account or log in to Instagram — share interesting moments with people who understand you.";
const MAIN_DESCRIPTION = "Share highlights, photos, and videos on the Photo Feed! Connect with friends, run your own blog, and watch Reels and Stories in real time. Join us!";
const CREATE_DESCRIPTION = "Upload your photos easily. Share your favorite moments, build your photography portfolio, and showcase your creativity with our community today.";

const AVAILABLE_TYPE = 'image/';
const PHOTO_MAX_FILE_SIZE = 60000000;
const FILE_UPLOAD_ERRORS = [
    //0 => 'There is no error, the file uploaded with success',    // ToDo: maybe not need
    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    3 => 'The uploaded file was only partially uploaded',
    4 => 'No file was uploaded',
    6 => 'Missing a temporary folder',
    7 => 'Failed to write file to disk.',
    8 => 'A PHP extension stopped the file upload.',
];