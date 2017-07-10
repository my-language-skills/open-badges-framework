<?php

$mostimportantlanguages = array();
$languages = array();
$handle = fopen("languages.tab", "r");
$handle2 = fopen("mostImportantLanguages.tab", "r");
if ($handle && $handle2) {
  while (($line = fgets($handle)) !== false) {
    $languages[] = $line;
  }
  while (($line = fgets($handle2)) !== false) {
    $mostimportantlanguages[] = $line;
  }
  fclose($handle);
  fclose($handle2);
} else {
  echo "Error : Can't open languages files !";
}

$content = '<?xml version="1.0" encoding="UTF-8" ?>
			<rss version="2.0"
				xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
				xmlns:content="http://purl.org/rss/1.0/modules/content/"
				xmlns:wfw="http://wellformedweb.org/CommentAPI/"
				xmlns:dc="http://purl.org/dc/elements/1.1/"
				xmlns:wp="http://wordpress.org/export/1.2/">

			<channel>
				<title>Badges4Languages</title>
				<link>http://releases.badges4languages.com</link>
				<description>Just another WordPress site</description>
				<pubDate>Thu, 06 Jul 2017 14:04:07 +0000</pubDate>
				<language>en-US</language>
				<wp:wxr_version>1.2</wp:wxr_version>
				<wp:base_site_url>http://releases.badges4languages.com</wp:base_site_url>
				<wp:base_blog_url>http://releases.badges4languages.com</wp:base_blog_url>';

$i = 0;

$content .= '<wp:term><wp:term_id>'.$i.'</wp:term_id><wp:term_taxonomy>job_listing_category</wp:term_taxonomy><wp:term_slug>most-important-languages</wp:term_slug><wp:term_parent></wp:term_parent><wp:term_name><![CDATA[Most important languages]]></wp:term_name></wp:term>';

foreach ($mostimportantlanguages as $language) {
  $i++;
  $content .= '<wp:term><wp:term_id>'.$i.'</wp:term_id><wp:term_taxonomy>job_listing_category</wp:term_taxonomy><wp:term_slug>'.$language.'</wp:term_slug><wp:term_parent>most-important-languages</wp:term_parent><wp:term_name><![CDATA['.$language.']]></wp:term_name></wp:term>';
}

$content .= '<wp:term><wp:term_id>'.$i.'</wp:term_id><wp:term_taxonomy>job_listing_category</wp:term_taxonomy><wp:term_slug>other-languages</wp:term_slug><wp:term_parent></wp:term_parent><wp:term_name><![CDATA[Other languages]]></wp:term_name></wp:term>';

foreach ($languages as $language) {
  $i++;
  $content .= '<wp:term><wp:term_id>'.$i.'</wp:term_id><wp:term_taxonomy>job_listing_category</wp:term_taxonomy><wp:term_slug>'.$language.'</wp:term_slug><wp:term_parent>other-languages</wp:term_parent><wp:term_name><![CDATA['.$language.']]></wp:term_name></wp:term>';
}

$content .= '</channel>
</rss>';

file_put_contents("languages.xml", $content);

?>
