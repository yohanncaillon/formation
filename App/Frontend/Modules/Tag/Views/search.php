<?php
$dataText = "";
foreach ($data_a as $Tag) {

    $dataText .= "<i class='tag-item'>" . htmlentities($Tag->name())."</i> ";
}
return $dataText;