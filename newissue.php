<?php

$title = getenv('ISSUE_TITLE');
$title = trim(str_replace('[addnew]','',$title));
$username = getenv('AUTHOR_USERNAME');
$pageContent =  getenv('ISSUE_BODY');
$template = file_get_contents('template.html');

function createSlug($t,$m=5){$t=trim(preg_replace('/[^a-zA-Z0-9]+/','-',strtolower($t)),'-');$w=explode('-',$t);return implode('-',array_slice($w,0,$m));}


$pattern = '/\/-_\/(description|html|js\d+)\r?\n(.*?)(?=\r?\n\/-_\/|$)/s';
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
            $newtemp = str_replace(array('<!--js3-->','id="moretests" style="display:none;"','id="mrbtn"'),array($js3,'id="moretests"','style="display:none;" id="mrbtn"'),$newtemp);
    }
    else {
    	$newtemp = str_replace('<!--js3-->','',$newtemp);
    }
    if (isset($data['js4'])) {
        $js4 = $data['js4'];
         $newtemp = str_replace('<!--js4-->',$js4,$newtemp);

    }
    else {
    	 $newtemp = str_replace('<!--js4-->','',$newtemp);
    }
   $newtemp = str_replace('<!-- tempdata -->','<p>'.strip_tags($data['description']).'<br>Submitted by: '.$username.'</p>',$newtemp);

$url = createSlug($title);
$url = $url.'-'.time();
file_put_contents('docs/tests/'.$url.'.html',$newtemp);
$exturl = 'https://avadhesh18.github.io//tests/'.$url.'.html';
$comment_body = 'Thank you for adding to jsPrefer. You can find your test at the following URL: 
'.$exturl.'';
} else {
 
    echo preg_last_error(); 

    echo "No matches found.";
    $comment_body = 'Thank you for adding to jsPrefer. Your submission was not pushed because there is some error in formatting of the submission.';
}


$github_api_url = 'https://api.github.com/repos/avadhesh18/jsPrefer/issues/'.getenv('ISSUE_NO').'/comments';
$payload = json_encode(array('body' => $comment_body));
$ch = curl_init($github_api_url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . getenv('ISSUE_TOKEN') 
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
echo $response;
    echo "Error sending comment to GitHub.";
} else {
echo $response;
    echo "Reply successfully posted to GitHub issue.";
}
?>
