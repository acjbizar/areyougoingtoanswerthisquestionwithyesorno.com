<?php

$a = get_a();

$config = parse_ini_file('../config.ini');

if(is_local())
{
    $db = new mysqli('localhost', 'root', NULL, 'mu');
}
else
{
    $db = new mysqli('db.acjs.net', $config['database_username'], $config['database_password'], 'acjs');
}

$defined = array(
    'yes' => 'yes',
    'no' => 'no'
);

if($a)
{
    $db->query('
        INSERT INTO `answer` (`created`, `a`, `server`)
        VALUES (' . time() . ', \'' . $db->real_escape_string($a) . '\', \'' . $db->real_escape_string(serialize($_SERVER)) . '\');') or die($db->error);
}

function get_a()
{
    if(isset($_SERVER['REQUEST_URI']))
    {
        $a = substr($_SERVER['REQUEST_URI'], (is_local() ? 48 : 1));
        return strtolower($a);
    }

    return FALSE;
}

function get_n($a = NULL)
{
    global $db;

    $rows = $db->query('SELECT COUNT(*) AS n FROM `answer` WHERE `a` = \'' . $db->real_escape_string($a) . '\';');

    if($rows->num_rows > 0)
    {
        $row = $rows->fetch_object();
        $n = $row->n;

        return intval($n);
    }

    return FALSE;
}

function is_local()
{
    return (isset($_SERVER['SERVER_ADDR']) AND in_array($_SERVER['SERVER_ADDR'], ['::1', '127.0.0.1']));
}

$r = '<!DOCTYPE html>';
$r .= '<html dir="ltr" lang="en">';
$r .= '<head>';
$r .= '<meta charset="utf-8">';
$r .= '<meta name="description" content="A question. By Alexander Christiaan Jacob, 2012.">';
$r .= '<meta name="twitter:card" content="summary">';
$r .= '<meta name="twitter:creator" content="@ACJ">';
$r .= '<meta name="viewport" content="initial-scale=1.0, width=device-width">';
$r .= '<meta property="fb:admins" content="509248955">';
$r .= '<meta property="og:description" content="A question. By Alexander Christiaan Jacob, 2012.">';
$r .= '<meta property="og:image" content="https://mimesia.net/apple-touch-icon.png">';
$r .= '<meta property="og:title" content="Are you going to answer this question with yes or no?">';
$r .= '<meta property="og:type" content="website">';
$r .= '<meta property="og:url" content="https://areyougoingtoanswerthisquestionwithyesorno.com/">';
$r .= '<title>Are you going to answer this question with yes or no?</title>';
$r .= '<link rel="apple-touch-icon-precomposed" href="https://mimesia.net/apple-touch-icon.png">';
$r .= '<link rel="author" href="https://alexanderchristiaanjacob.com/" title="Alexander Christiaan Jacob">';
$r .= '<link rel="canonical" href="https://areyougoingtoanswerthisquestionwithyesorno.com/">';
$r .= '<link rel="shortcut icon" href="https://mimesia.com/favicon.ico">';
$r .= '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tulpen+One">';
$r .= '<script>';
$r .= '  var _gaq = _gaq || [];';
$r .= '  _gaq.push([\'_setAccount\', \'UA-6227584-36\']);';
$r .= '  _gaq.push([\'_setDomainName\', \'.areyougoingtoanswerthisquestionwithyesorno.com\']);';
$r .= '  _gaq.push([\'_trackPageview\']);';
$r .= '  (function() {';
$r .= '    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;';
$r .= '    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';';
$r .= '    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);';
$r .= '  })();';
$r .= '</script>';
$r .= '<style>';
$r .= '*{border:0;font-size:1em;margin:0;outline:0;padding:0;text-decoration:none}';
$r .= 'a{color:#36f;display:block;height:100%;width:100%}';
$r .= 'body,html{height:100%}';
$r .= 'h1{font-size:4.5em;line-height:.75;padding:.75em;text-align:center}';
$r .= 'html{background:#fff;font:normal 100%/1.5 \'Tulpen One\',sans-serif;text-rendering:optimizelegibility}';
$r .= 'li{display:block;float:left;height:100%;text-align:center;width:50%}';
$r .= 'section{display:block;height:50%}';
$r .= 'ul{height:100%}';
$r .= 'a:hover{background:#000;color:#fff}';
$r .= '.a{display:block;font-size:9em;line-height:.75;padding:.375em .375em 0 .375em}';
$r .= '.active a{background:#000;color:#fff}';
$r .= '.n{display:block;font-size:4.5em;line-height:.75;padding:.375em .375em 0 .375em}';
$r .= '.three .no,.three .yes{width:25%}';
$r .= '#a{height:50%}';
$r .= '</style>';
$r .= '</head>';
$r .= '<body>';
$r .= '<section id="q">';
$r .= '<h1>Are you going to answer this question with yes or no?</h1>';
$r .= '</section>';
$r .= '<div id="a">';
if($a AND !in_array($a, $defined))
{
    $r .= '<ul class="three">';
    $r .= '<li class="active"><a href="' . htmlspecialchars($a) . '"><span class="a">' . htmlspecialchars($a) . '</span>';
    $r .= '<span class="n">(' . get_n($a) . ')</span></a></li>';
    $r .= '<li class="yes"><a href="yes"><span class="a">Yes</span><span class="n">(' . get_n('Yes') . ')</span></a></li>';
    $r .= '<li class="no"><a href="no"><span class="a">No</span><span class="n">(' . get_n('No') . ')</span></a></li>';
    $r .= '</ul>';
}
else
{
    $r .= '<ul class="two">';
    $r .= '<li class="' . ($a === 'yes' ? 'active ' : '') . 'yes"><a href="yes">';
    $r .= '<span class="a">Yes</span><span class="n">(' . get_n('Yes') . ')</span></a></li>';
    $r .= '<li class="' . ($a === 'no' ? 'active ' : '') . 'no"><a href="no">';
    $r .= '<span class="a">No</span><span class="n">(' . get_n('No') . ')</span></a></li>';
    $r .= '</ul>';
}
$r .= '</div>';
$r .= '</body>';
$r .= '</html>';

header('Content-Type:text/html; charset=utf-8');

echo $r;
