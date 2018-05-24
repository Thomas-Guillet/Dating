<?php

function csv_to_array($filename='', $delimiter=',')
{
    $file_headers = @get_headers($filename);

    if($file_headers[0] == 'HTTP/1.0 404 Not Found'){
      return FALSE;
    } else if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found'){
      return FALSE;
    }

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE){
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}


 ?>
