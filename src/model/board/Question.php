<?php

class Question
{
    public function saveFiles($files)
    {
        $error = false;
        $upload_files = array();
        $upload_dir = FILE_ROOT . '/question/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir);
        }
        for ($i = 0; $i < count($files); $i++) {
            if (move_uploaded_file($files[$i]['tmp_name'], $upload_dir . time() . '_' . $files[$i]['name'])) {
                $upload_files[] = time() . '_' . $files[$i]['name'];
            } else {
                $error = true;
            }
        }
        return ($error) ? false : $upload_files;
    }

    public function insertQuestion($data)
    {
        $db = new DB();
        $query = "INSERT INTO _question
                SET ";
        $insertData = '';
        for ($i = 0; $i < count($data); $i++) {
            $key = array_keys($data)[$i];
            $value = array_values($data)[$i];
            $comma = ($i == 0) ? ' ' : ',';
            $insertData .= $comma . " {$key} = \"{$value}\"";
        }
        $query .= $insertData;
        return $db->execute($query);
    }
}