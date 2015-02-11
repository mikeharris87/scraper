<?php

// Function to make GET request using cURL
function curlGet($url) {

    $ch = curl_init(); // Initialize the curl session

    // Setting cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_URL, $url);

    $results = curl_exec($ch);

    curl_close($ch);

    return $results;

}

$packtBook = array(); // Declare array to store scraped book data

function returnXPathObject($item) {
    $xmlPageDom = new DOMDocument();
    $xmlPageDom->loadHTML($item);
    $xmlPageXPath = new DOMXPath($xmlPageDom);
    return $xmlPageXPath;
}
$packtPage = curlGet('https://www.packtpub.com/web-development/learning-ext-js');

$packtPageXpath = returnXPathObject($packtPage);

$title = $packtPageXpath->query('//h1');

// If title exists
if ($title->length > 0) {
    $packtBook['title'] = $title->item(0)->nodeValue;
}
$release = $packtPageXpath->query('//time');

// If release date exists
if ($release->length > 0) {
    $packtBook['release'] = $release->item(0)->nodeValue;
}
$overview = $packtPageXpath->query('//div[@class="overview_left"]');
// If overview exists
if ($overview->length > 0) {
    $packtBook['overview'] = trim($overview->item(0)->nodeValue);
}
$author = $packtPageXpath->query('//div[@class="bpright"]/div[@class="author"]/a');
if ($author->length > 0) {
    // For each author
    for ($i = 0; $i < $author->length; $i++) {
        $packtBook['authors'][] = $author->item($i)->nodeValue;
 }
}

print_r($packtBook);

?>