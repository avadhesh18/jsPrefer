<?php

$title = getenv('ISSUE_TITLE');
$title = trim(str_replace('[addnew]','',$title));
$username = getenv('AUTHOR_USERNAME')
$pageContent =  getenv('ISSUE_BODY');
$template = file_get_contents('template.html');
function createSlug($t,$m=5){$t=trim(preg_replace('/[^a-zA-Z0-9]+/','-',strtolower($t)),'-');$w=explode('-',$t);return implode('-',array_slice($w,0,$m));}

$pattern = '/\/-_\/(description|html|js\d+)\n(.*?)(?=\n\/-_\/|$)/s';
if (preg_match_all($pattern, $pageContent, $matches)) {
    $data = [];
    for ($i = 0; $i < count($matches[0]); $i++) {
        $key = $matches[1][$i];
        $value = trim($matches[2][$i]);
        $data[$key] = $value;
    }

    $desc = $data['description'];
    $html = $data['html'];
    $js1 = $data['js1'];
    $js2 = $data['js2'];
    
    $newtemp = str_replace(array('<!--title-->','<!--html-->','<!--js1-->','<!--js2-->'),array($title,$html,$js1,$js2),$template);
    if (isset($data['js3'])) {
        $js3 = $data['js3'];
            $newtemp = str_replace('<!--js3-->',$js3,$newtemp);
    }
    else {
    	$newtemp = str_replace('',$js3,$newtemp);
    }
    if (isset($data['js4'])) {
        $js4 = $data['js4'];
         $newtemp = str_replace('<!--js4-->',$js4,$newtemp);

    }
    else {
    	 $newtemp = str_replace('<!--js4-->',$js4,$newtemp);
    }
   $newtemp = str_replace('<!-- tempdata -->','<p>'.$data['description'].'<br>Submitted by: '.$username.'</p>',$newtemp);

$url = createSlug($title);
$url = $url.'-'.time();
file_put_contents('docs/tests/'.$url.'.html',$newtemp);
} else {
    echo "No matches found.";
}
?>
