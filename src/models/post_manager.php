<?php

class PostManager
{
    protected function replaceAllHashtags(string $message): string
    {
        if (preg_match_all('/\s#(\w+)/', $message, $matches)) {
            foreach ($matches[1] as $match) {
                $replacement = '<a href="/tendances/' . $match . '" class="text-primary">#' . $match . '</a>';
                $message = str_replace('#' . $match, $replacement, $message);
            }
        }

        return $message;
    }

    protected function replaceAllArobases(string $message): string
    {
        if (preg_match_all('/\s@(\w+)/', $message, $matches)) {
            foreach ($matches[1] as $match) {
                $replacement = '<a href="/profile/' . $match . '" class="text-primary">@' . $match . '</a>';
                $message = str_replace('@' . $match, $replacement, $message);
            }
        }

        return $message;
    }

    protected function replaceAllLinks(string $message): string
    {
        $message = preg_replace_callback('/\s(?:https?:\/\/\S+)/', function ($matches) {
            $url = $matches[0];

            if (preg_match('/\s(?:https?:\/\/)/', $url)) {
                return '<br><a href="' . $url . '" class="text-primary" target="_blank">' . $url . '</a>';
            } else {
                return $url;
            }
        }, $message);

        return $message;
    }

    protected function generateShortUrl(string $file_name): string
    {
        $hash = md5($file_name);
        return substr($hash, 0, 6);
    }
}