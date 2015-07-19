<?php
/*      GET UTF-8 READY
######################################################*/
    header('Content-Type: text/html; charset=utf-8');

/*      CHECK IF NEW JSON IS NEEDED
######################################################*/
@$ts = filemtime('gallneukirchen.json');
$today = strtotime('today midnight');
if($ts > $today){
    header('Content-Type: application/json');
    include('gallneukirchen.json');
    exit();
}

/*      FUNCTIONS & INCLUDES
######################################################*/
    include("simple_html_dom.php");

    function preParse($html){
        /* REMOVE TAG'S */
        $html = str_replace("<span>", "", $html);
        $html = str_replace("</span>", "", $html);
        $html = str_get_html($html);
        return $html;
    }

    function remURL($text){
        /* REMOVE URL */
        preg_match('/\>(.*)<\/a>/', $text, $match);
        if(!$match){
            return $text;
        }else{
            return $match[1];
        }
    }

/*      VAR'S
######################################################*/
    $html = '';
    $datum = date("d.m.Y");

/*      GET TABLE FROM GALLNEUKIRCHEN.AT
######################################################*/
    $source = file_get_contents("http://www.gallneukirchen.at/system/web/veranstaltung.aspx?typ=0&gnr_search=91&bezirkonr=0&page=0&vdatum=$datum&bdatum=31.12.9999&umkreis=&blnr=&useronr=0&datum=$datum&menuonr=218254390");

    preg_match("'<table summary=\"Veranstaltungen\" border=\"0\">(.*?)</table>'si", $source, $match);
    if(!$match) exit();
    $html = '<table border="1">';
    $html .= str_replace("</table>", "", $match[1]);

    for($i=1; $i<5; $i++){
        $source = file_get_contents("http://www.gallneukirchen.at/system/web/veranstaltung.aspx?typ=0&gnr_search=91&bezirkonr=0&page=$i&vdatum=$datum&bdatum=31.12.9999&umkreis=&blnr=&useronr=0&datum=$datum&menuonr=218254390");

    preg_match("'<table summary=\"Veranstaltungen\" border=\"0\">(.*?)</table>'si", $source, $match);
    if(!$match) exit();

    $html .= str_replace('<table summary="Veranstaltungen" border="0">
			<tr class="even">
				<th scope="col">Datum</th><th scope="col">Veranstaltung</th><th scope="col">Ort</th>
			</tr>', "", str_replace("</table>", "", $match[0]));
    }
    $html .= "</table>";

/*      PARSE
######################################################*/
    $html = preParse($html);
    $row_count=0;
    $json = array();
    foreach ($html->find('tr') as $row) {
            if($row_count == 0){$row_count++; continue;}
        
            $datum = $row->find('td',0)->innertext;
            $titel = $row->find('td',1)->innertext;
            $ort = $row->find('td',2)->innertext;

            $json[$row_count]['datum'] = remURL($datum);
            $json[$row_count]['titel'] = remURL($titel);
            $json[$row_count]['ort'] = remURL($ort);

            $row_count++;
        }

/*      OUTPUT
######################################################*/
    $json = json_encode($json);
    $file = fopen("gallneukirchen.json","w");
    fwrite($file, $json);
    fclose($file);
    include("gallneukirchen.json");
?>