<?php

class ViewUploadForm extends Programster\AbstractView\AbstractView
{
    protected function renderContent()
    {
?>

<h2>Upload File</h2>
<form enctype="multipart/form-data" action="/documents" method="POST">
    <input name="file_input_name" type="file" /><br /><br />
    <input type="submit" value="Send File" />
</form>

<hr>

<h2>Search</h2>
<form action="/documents/search" method="GET">
    <input name="search" type="text" /><br /><br />
    <input type="submit" value="Send File" />
</form>

<?php
    }
}
