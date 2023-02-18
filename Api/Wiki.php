<?php


class Wiki
{
    public function get_wiki($search)
    {
        $api_url = "https://en.wikipedia.org/w/api.php?action=query&format=json&explaintext&exintro&utf8=&prop=extracts&titles=".$search."&redirects=true";
        $api_url = str_replace(' ', '%20', $api_url);

        $data = json_decode(@file_get_contents($api_url));
        foreach ($data->query->pages as $key => $value){
            $pageId = $key;
            break;
        }
        if($content = $data->query->pages->$pageId->extract){
            return ['content' => $content, 'code' => true];
        }else{
            return ['content' => 'Not result found', 'code' => false];
        }
    }

}