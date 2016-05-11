<?php
$dataText = "";
foreach ($data_a as $Tag) {

    $dataText .= $Tag->name()." ";
}
return $dataText;