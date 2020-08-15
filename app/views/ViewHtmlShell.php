<?php

class ViewHtmlShell extends Programster\AbstractView\AbstractView
{
    private string $m_content;


    public function __construct(string $content)
    {
        $this->m_content = $content;
    }

    protected function renderContent()
    {

?>

 <!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Programster's Document Store</title>
  <meta name="author" content="Programster">
  <meta name="description" content="A place to upload and search documents.">
  <meta name="keywords" content="search, documents">
  <!--<link rel="stylesheet" href="/css/reset.css" type="text/css"> -->
  <link rel="stylesheet" href="/css/style.css" type="text/css">
  </head>
  <body>
      <?= $this->m_content; ?>
  </body>
</html>


<?php
    }

}

